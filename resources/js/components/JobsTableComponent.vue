<template>
    <div v-bind:style="{paddingTop:'25px'}">
        <b-row>
            <b-col md-12 class="text-center">
                <h4>Jobs</h4>
            </b-col>
        </b-row>
        <hr/>
        <b-row class="justify-content-center">
            <div class="col-md-12">
                <vue-good-table
                    mode="remote"
                    @on-page-change="onPageChange"
                    @on-sort-change="onSortChange"
                    @on-per-page-change="onPerPageChange"
                    @on-search="onSearch"
                    :isLoading.sync="isLoading"
                    :search-options="{
                                enabled: true,
                                trigger: 'enter'
                              }"
                    :sort-options="{
                                enabled: true,
                                initialSortBy: {field: 'job_created_at', type: 'desc'}
                              }"
                    :pagination-options="{enabled: true}"
                    :totalRows="totalRecords"
                    :rows="tableRows"
                    styleClass="vgt-table striped"
                    :columns="columns"/>
            </div>
        </b-row>
    </div>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import {VueGoodTable} from 'vue-good-table';

export default {
    name: "JobsTableComponent",
    props: {
        'resync': Boolean
    },
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
                    label: 'Created',
                    field: 'job_created_at',
                    type: 'date',
                    dateInputFormat: 'yyyy-MM-dd HH:mm:ss',
                    dateOutputFormat: 'yyyy-MM-dd HH:mm:ss',
                },
                {
                    label: 'Description',
                    field: 'description',
                },
            ],
            totalRecords: 0,
            tableRows: [],
            serverParams: {
                search: '',
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
            this.loadItems(false);
        },

        onPerPageChange(params) {
            this.updateParams({perPage: params.currentPerPage});
            this.loadItems(false);
        },

        onSearch(params) {
            this.serverParams.search = params.searchTerm
            this.loadItems(false);
        },

        onSortChange(params) {
            this.updateParams({
                orderDirection: params[0].type,
                orderBy: params[0].field,
            });
            this.loadItems(false);
        },

        loadItems(update) {
            this.isLoading = true;
            let params = this.serverParams;
            let page = params.page;
            if (params.search) {
                page = 1;
            }
            window.axios.get("/api/jobs?search=" + params.search + "&page="
                + page + "&perPage=" + params.perPage + "&orderBy=" + params.orderBy + "&orderDirection=" + params.orderDirection + "&update=" + update)
                .then(response => {
                    this.totalRecords = response.data.total;
                    this.tableRows = response.data.data;
                    this.$emit('syncComplete');
                    this.isLoading = false;
                    window.scrollTo(0, 0);
                    if (update) {
                        Vue.$toast.open('Data is successfully synced with Public APIs!');
                    }
                }).catch(err => {
                console.error(err);
                this.isLoading = false;
                if (update) {
                    this.$emit('syncComplete');
                }
                Vue.$toast.open({message: 'There was an issue retrieving the data', type: 'error'});
            });
        },
    },
    watch: {
        resync: function (newVal, oldVal) {
            if (newVal) {
                this.loadItems(true)
            }
        }
    }
}
</script>

<style scoped>

</style>
