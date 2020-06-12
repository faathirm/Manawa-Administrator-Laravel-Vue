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
                                <b-icon icon="circle" type="is-success" v-if="props.row.verified_at != null" />
                                <b-icon icon="circle" type="is-danger" v-if="props.row.denied_at != null" />
                                <b-icon icon="circle" type="is-info" v-if="props.row.denied_at == null && props.row.verified_at == null"/>
                                {{ props.row.id }}
                            </b-table-column>
                            <b-table-column field="name" label="NAME" cellClass="text-capitalize">
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.transaction.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.transaction.customer.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.transaction.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.transaction.customer.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="verified_at" label="STATUS" cellClass="text-capitalize">
                                <template v-if="props.row.verified_at != null">Verified</template>
                                <template v-if="props.row.denied_at != null">Denied</template>
                                <template v-if="props.row.denied_at == null && props.row.verified_at == null">Unverified</template>
                            </b-table-column>
                            <b-table-column field="id_transaction" label="MESSAGE" cellClass="text-capitalize">
                                <template v-if="props.row.transaction != null">{{ props.row.transaction.status }}</template>
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
                                                <img @click="isImageModalActive = true, modalPhoto = props.row.photo_url" class="img-thumbnail" style="max-height:500px;" :src="props.row.photo_url">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center justify-content-end">
                                                <div>
                                                    <b-field label="Status" label-position="on-border">
                                                        <b-select placeholder="Select status" v-model="statusnya" expanded class="d-block mb-2">
                                                            <option value="1">Unverify</option>
                                                            <option value="2">Verify</option>
                                                            <option value="3">Deny</option>
                                                        </b-select>
                                                    </b-field>
                                                    <div class="buttons has-addons align-self-center">
                                                        <b-button type="is-dark" icon-left="delete" @click="deleteData(props.row.id)" outlined>Delete Data</b-button>
                                                        <b-button @click="addData(props.row.id)" type="is-dark">Submit</b-button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </template>
                        <template slot="footer">
                            <th class="is-hidden-mobile"/>
                            <th class="is-hidden-mobile"><div class="th-wrap">TID</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">NAME</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">STATUS</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">MESSAGE</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">DATE</div></th>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
        <b-modal :active.sync="isImageModalActive">
            <p class="image">
                <img :src="this.modalPhoto">
            </p>
        </b-modal>
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
                showDetailIcon: true,
                //Update
                idnya:'',
                statusnya:'',
                //Modal
                isImageModalActive: false,
                modalPhoto:''

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
                axios.get(`http://localhost:8000/api/transaction/confirmation?${params}`).then(response => {
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
            },
            toggle(row) {
                this.$refs.table.toggleDetails(row)
            },
            addData(id){
                axios.post('http://localhost:8000/api/transaction/confirmation', {
                    status: this.statusnya,
                    id: id
                }).then(response => {
                    console.log(response);
                    this.$buefy.toast.open({message: `Update Success`, position: 'is-bottom'})
                    this.loadAsyncData();
                })
            },
            deleteData(id){
                this.$buefy.dialog.confirm({
                    title: 'Deleting data',
                    message: 'Are you sure you want to <b>delete</b> this data? This action cannot be undone.',
                    confirmText: 'Delete Account',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        axios.post('http://localhost:8000/api/transaction/confirmation/delete', {
                            result: id
                        }).then(response => {
                            console.log(response)
                            this.$buefy.toast.open({message: `Delete Success`, position: 'is-bottom'})
                            this.loadAsyncData()
                        })
                    }
                })
            }
        },
        created(){
            this.loadAsyncData();
        }
    };
</script>
