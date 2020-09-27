<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <vue-good-table
                    mode="remote"
                    @on-page-change="onPageChange"
                    @on-sort-change="onSortChange"
                    @on-per-page-change="onPerPageChange"
                    @on-search="onSearch"
                    :search-options="{
                        enabled: true,
                        trigger: 'enter'
                      }"
                    :sort-options="{
                        enabled: true,
                        initialSortBy: {field: 'company', type: 'asc'}
                      }"
                    :pagination-options="{enabled: true}"
                    :totalRows="totalRecords"
                    :rows="tableRows"
                    :columns="columns"/>
            </div>
        </div>
    </div>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
    export default {
        components: {
            VueGoodTable
        },
        data() {
            return {
                isLoading: false,
                columns: [
                    {
                        label: 'Company',
                        field: 'company',
                    },
                    {
                        label: 'Job Title',
                        field: 'job_title',
                    },
                    {
                        label: 'Description',
                        field: 'description',
                    },
                ],
                totalRecords: 0,
                tableRows: [],
                serverParams: {
                    search:'',
                    orderBy: '',
                    orderDirection: '',
                    sort: {
                        field: '',
                        type: '',
                    },
                    page: 1,
                    perPage: 10
                }
            }
        },
        methods: {
            updateParams(newProps) {
                this.serverParams = Object.assign({}, this.serverParams, newProps);
            },

            onPageChange(params) {
                this.updateParams({page: params.currentPage});
                this.loadItems();
            },

            onPerPageChange(params) {
                this.updateParams({perPage: params.currentPerPage});
                this.loadItems();
            },

            onSearch(params) {
                this.serverParams.search = params.searchTerm
                this.loadItems();
            },

            onSortChange(params) {
                this.updateParams({
                        orderDirection: params[0].type,
                        orderBy: params[0].field,
                });
                this.loadItems();
            },

            loadItems() {
                let params = this.serverParams;
                window.axios.get("/api/jobs?search="+params.search+"&page="
                    +params.page+"&perPage="+params.perPage+"&orderBy="+params.orderBy+"&orderDirection="+params.orderDirection).then(response => {
                    this.totalRecords = response.data.total;
                    this.tableRows = response.data.data;
                });
            },
        },
        mounted() {
            this.loadItems();
            console.log('Component mounted.')
        }
    }
</script>
