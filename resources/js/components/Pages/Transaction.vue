<template>
    <section>
        <div>
            <div class="row d-flex justify-content-end">
                <div class="col-md-4 offset-md-8">
                    <b-field position="is-right">
                        <b-input placeholder="Search..." type="search" icon="magnify" v-model="searchInput"></b-input>
                        <p class="control">
                            <b-button class="button is-primary" v-on:click="onSearch()">Search</b-button>
                        </p>
                    </b-field>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <b-table
                        :narrowed="true"
                        ref="table"

                        :sort-icon="sortIcon"
                        :sort-icon-size="sortIconSize"

                        :loading="loading"
                        :data="admins"

                        detailed
                        detail-key="id"
                        @details-open="(row, index) => {}"
                        :show-detail-icon="showDetailIcon"

                        paginated
                        backend-pagination
                        :total="total"
                        :per-page="10"
                        :current-page="currentPage"
                        @page-change="onPageChange"

                        backend-sorting
                        :default-sort-direction="defaultSortOrder"
                        :default-sort="[sortField, sortOrder]"
                        @sort="onSort">
                        <template slot-scope="props">
                            <b-table-column field="id" label="TID" cellClass="text-capitalize" sortable>
                                {{ props.row.id }}
                            </b-table-column>
                            <b-table-column field="id_user" label="NAME" cellClass="text-capitalize">
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="journal_desc" label="DESCRIPTION" cellClass="text-capitalize" sortable>
                                <router-link :to="'/livestock/customer/'+props.row.livestock.id" v-if="props.row.journal_desc == 'Pembelian ternak'">
                                    {{ props.row.journal_desc }}
                                </router-link>
                                <template v-else>
                                    {{ props.row.journal_desc }}
                                </template>
                            </b-table-column>
                            <b-table-column field="value" label="AMOUNT" sortable>
                                <b-tag :type="props.row.value <= 0 ? 'is-danger' : 'is-info'">{{props.row.value < 0 ? 'cr.':'dr.'}}</b-tag> {{ Math.abs(props.row.value) | currency }}
                            </b-table-column>
                            <b-table-column field="created_at" label="DATE" cellClass="text-capitalize" sortable>
                                {{ props.row.created_at }}
                            </b-table-column>
                        </template>
                        <template slot="detail" slot-scope="props">
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-md-6">
                                                <p>
                                                    <strong class="text-capitalize">{{props.row.customer.name}}</strong><br>
                                                    <router-link :to="'/livestock/customer/'+props.row.livestock.id" v-if="props.row.journal_desc == 'Pembelian ternak'">
                                                        {{ props.row.journal_desc }}
                                                    </router-link>
                                                    <template v-else>
                                                        {{ props.row.journal_desc }}
                                                    </template>
                                                </p>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end align-items-center">
                                                <b-tag class="mr-2" :type="props.row.value <= 0 ? 'is-danger' : 'is-info'">{{props.row.value < 0 ? 'cr.':'dr.'}}</b-tag>  {{ Math.abs(props.row.value) | currency }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </template>
                        <template slot="footer">
                            <th class="is-hidden-mobile"/>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">TID</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">NAME</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">DESCRIPTION</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">AMOUNT</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">DATE</div>
                            </th>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    import {Avatar} from "vue-avatar";

    export default {
        components: {
            Avatar
        },
        data() {
            return {
                //TABLE
                total: 0,
                currentPage: 1,
                sortField: 'id',
                sortOrder: 'desc',
                defaultSortOrder: 'desc',
                searchData: null,
                searchInput:null,
                loading:false,
                page:1,
                sortIcon: 'chevron-up',
                sortIconSize: 'is-small',
                admins: [],
                //DetailedTabel
                defaultOpenedDetails: [1],
                showDetailIcon: true

            };
        },
        methods: {
            loadAsyncData() {
                const params = [
                    `search=${this.searchData}`,
                    `sort_by=${this.sortField}`,
                    `sort_order=${this.sortOrder}`,
                    `page=${this.page}`
                ].join('&');
                this.loading = true;
                axios.get(`http://manawa.akugap.tech/api/transaction?${params}`).then(response => {
                    this.admins = response.data["data"];
                    let currentTotal = response.data["total"];
                    if(response.data["total"] / 10 > 1000){
                        currentTotal = this.perPage * 1000;
                    }
                    this.currentPage = response.data["current_page"];
                    this.total = currentTotal;

                    this.loading = false;
                    if(this.searchData || this.currentPage == 1){
                        this.$buefy.toast.open({message: `${this.total} Data Displayed`, position: 'is-bottom'})
                    }
                }).catch((error) => {
                    this.admins = [];
                    this.total = 0;
                    this.loading = false;
                    throw error;
                });

            },
            onPageChange(page) {
                this.page = page;
                this.loadAsyncData();
            },
            onSort(field, order) {
                this.sortField = field
                this.sortOrder = order
                this.currentPage = 1
                this.onPageChange(1);
            },
            onSearch(){
                this.searchData = this.searchInput;
                this.currentPage = 1;
                this.onPageChange(1);
                // this.loadAsyncData();
            },
            toggle(row) {
                this.$refs.table.toggleDetails(row)
            },
        },
        created(){
            this.loadAsyncData();
        }
    };
</script>
