<template>

    <div class="countries">
        <div class="filter">
            <model-select
                :options="options"
                v-model="tableData.searchCountry"
                placeholder="select country"
                class="select"
               >
            </model-select>

            <!-- string value -->
            <model-select
                :options="options2"
                v-model="tableData.searchState"
                placeholder="select state"
                class="select"
            >
            </model-select>
        </div>

        <table-component
            :columns="columns"
            :sortKey="sortKey"
            :sortOrders="sortOrders"
            @sort="sortBy"
        >
            <tbody v-for="country in countries" :key="country.id">
                <td>{{country.country}}</td>
                <td>{{country.state === '0'? 'NOK':'OK' }}</td>
                <td>{{country.country_code}}</td>
                <td>{{country.phone_number}}</td>
            </tbody>
        </table-component>

        <pagination-component
            :pagination="pagination"
            @prev="getCountries(pagination.prevPageUrl)"
            @next="getCountries(pagination.nextPageUrl)"
        ></pagination-component>
    </div>
</template>

<script>
    import TableComponent from './TableComponent.vue'
    import PaginationComponent from './PaginationComponent.vue'
    import { ModelSelect } from 'vue-search-select'
    export default {
        components: {TableComponent,PaginationComponent,ModelSelect},
        created() {
            this.getCountryNames();
            this.getCountries();
        },
        data() {
            let sortOrders = {};

            let columns = [
                {width: '25%',label: 'Country', name: 'country'},
                {width: '25%',label: 'State', name: 'state'},
                {width: '25%',label: 'Country Code', name: 'country_code'},
                {width: '25%',label: 'Phone Num', name: 'phone_number'},
            ];
            columns.forEach((column) => {
                sortOrders[column.name] = -1;
            });

            return {
                options: [],

                options2: [
                    { value: '1', text: 'valid' },
                    { value: '0', text: 'not valid' },
                    { value: null, text: 'valid and not valid' },

                ],

                countries: [],
                columns: columns,
                sortKey: 'country',
                sortOrders: sortOrders,

                tableData: {
                    sync: 0,
                    length: 10,
                    searchCountry: '',
                    searchState: '',
                    column: 0,
                    dir: 'desc',
                },
                pagination: {
                    nextPageUrl: '',
                    prevPageUrl: '',
                },
            }
        },
        computed: {
                searchCountry() {
                    return this.tableData.searchCountry;
                },
                searchState() {
                    return this.tableData.searchState;
                }
            },

        watch: {
            searchCountry() {
                this.getCountries();
            },
            searchState() {
                this.getCountries();
            }
        },
        methods: {
            getCountryNames(url = '/country-names') {
                axios.get(url)
                    .then(response => {
                        let data = response.data;
                        data.data.forEach((country) => {
                            let option = { value: country.country, text: country.country };
                            this.options.push(option);
                        });
                        this.options.unshift({ value: null, text: 'all countries' });
                    })
            },
            getCountries(url = '/countries') {
                this.tableData.sync++;

                axios.get(url, {params: this.tableData})
                    .then(response => {
                        let data = response.data;
                        if (this.tableData.sync == data.sync) {
                            this.countries = data.data.data;
                            this.configPagination(data.data);
                        }
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            configPagination(data) {
                this.pagination.nextPageUrl = data.next_page_url;
                this.pagination.prevPageUrl = data.prev_page_url;
            },
            sortBy(key) {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                console.log(key)
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';

                this.getCountries();
            },
            getIndex(array, key, value) {
                return array.findIndex(i => i[key] === value)
            },

        },
    }
</script>

<style >

    body {
        margin: 0;
        box-sizing: border-box;
    }
    table {
        width: 100%;
    }
    .countries {
        margin: 5rem auto;
        width: 80%
    }
    .filter {
        width: 50%;
        display: flex;
        margin-bottom: 3rem;
    }
    .filter .select {
        margin-left: 1rem;
    }
    .sorting {
        background-image: url('/images/sort_both.png');
        background-repeat: no-repeat;
        background-position: center right;
    }
    .sorting_asc {
        background-image: url('/images/sort_asc.png');
        background-repeat: no-repeat;
        background-position: center right;
    }
    .sorting_desc {
        background-image: url('/images/sort_desc.png');
        background-repeat: no-repeat;
        background-position: center right;
    }

</style>
