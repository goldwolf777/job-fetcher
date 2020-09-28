<template>
    <div>
        <div>
            <b-navbar toggleable="lg" variant="light" sticky>
                <b-navbar-brand>
                    <img :src="'/images/jobilla_logo_color.png'" width="10%" alt="jobilla-logo">
                </b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav class="ml-auto" right>
                        <b-nav-item target="_blank" href="https://github.com/goldwolf777/job-fetcher">Info</b-nav-item>
                        <b-button variant="primary" :disabled="disableButton" v-on:click="resyncData">Sync</b-button>
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>
        </div>
        <b-container>
                <jobs-table-component :resync="resync" @syncComplete="resync = false; disable=false"></jobs-table-component>
        </b-container>
    </div>
</template>

<script>
    import JobsTableComponent from "./JobsTableComponent";

    export default {
        components: {
            JobsTableComponent,
        },
        data() {
            return {
                resync: false,
                disableButton: false
            }
        },
        methods: {
            resyncData() {
                this.resync = true;
            },
        }
    }
</script>
