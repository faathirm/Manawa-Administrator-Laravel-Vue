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
                                <b-icon icon="circle" type="is-success" v-if="props.row.withdraw_status.status == 'Selesai'"/>
                                <b-icon icon="circle" type="is-info" v-if="props.row.withdraw_status.status == 'Dalam Proses'"/>
                                <b-icon icon="circle" type="is-danger" v-if="props.row.withdraw_status.status == 'Dibatalkan'"/>
                                {{ props.row.id }}
                            </b-table-column>
                            <b-table-column field="name" label="NAME" cellClass="text-capitalize">
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="amount" label="AMOUNT" cellClass="text-capitalize" sortable>
                                {{props.row.amount | currency}}
                            </b-table-column>
                            <b-table-column field="customer_account.acc_bank" label="BANK" cellClass="text-capitalize">
                                {{props.row.customer_account.acc_bank}}
                            </b-table-column>
                            <b-table-column field="status" label="STATUS" cellClass="text-capitalize">
                                {{props.row.withdraw_status.status}}
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
                                            <div class="col-md-6 d-flex align-items-center">
                                                <img @click="isImageModalActive = true, modalPhoto = props.row.withdraw_status.photo_url" class="img-thumbnail" v-if="props.row.withdraw_status.photo_url != null && props.row.withdraw_status.status == 'Selesai'" style="max-height:450px;" :src="props.row.withdraw_status.photo_url">
                                                <b-tag v-else>Withdrawal {{props.row.withdraw_status.status}}</b-tag>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center justify-content-end">
                                                <div>
                                                    <b-upload v-model="file" expanded id="file" ref="file" v-on:change="handleFileUpload()">
                                                        <a class="button is-primary is-fullwidth">
                                                            <b-icon icon="upload"></b-icon>
                                                            <span>{{ file.name || "Click to upload"}}</span>
                                                        </a>
                                                    </b-upload>
                                                    <div class="buttons has-addons align-self-center">
                                                        <b-button type="is-dark" icon-left="delete" @click="deleteData(props.row.id)" outlined>Delete Data</b-button>
                                                        <b-button type="is-dark" icon-left="close" @click="declineData(props.row.id)" outlined>Decline</b-button>
                                                        <b-button @click="submitFile(props.row.id)" type="is-dark">Submit</b-button>
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
                            <th class="is-hidden-mobile"><div class="th-wrap">AMOUNT</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">BANK</div></th>
                            <th class="is-hidden-mobile"><div class="th-wrap">STATUS</div></th>
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
                file: {},
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
                axios.get(`http://localhost:8000/api/withdrawal?${params}`).then(response => {
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
            deleteData(id){
                this.$buefy.dialog.confirm({
                    title: 'Deleting data',
                    message: 'Are you sure you want to <b>delete</b> this data? This action cannot be undone.',
                    confirmText: 'Delete Account',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        axios.post('http://localhost:8000/api/withdrawal/delete', {
                            id: id
                        }).then(response => {
                            this.$buefy.toast.open({message: `Delete Success`, position: 'is-bottom'})
                            this.loadAsyncData()
                        })
                    }
                })
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            submitFile(id){
                let formData = new FormData();
                formData.append('id',id);
                if(this.file.name){
                    formData.append('file',this.file);
                    axios.post('http://localhost:8000/api/withdrawal/upload', formData, {headers: {'Content-Type': 'multipart/form-data'}
                    }).then(response => {
                        this.file = {};
                        this.$buefy.toast.open({message: `Upload Success`, position: 'is-bottom'});
                        this.loadAsyncData();
                    }).catch(function(){
                        this.$buefy.toast.open({message: `Upload Failed`, position: 'is-bottom', type: 'is-danger'});
                    });
                }else{
                    this.$buefy.toast.open({message: `Upload Failed`, position: 'is-bottom', type: 'is-danger'});
                }
            },
            declineData(id){
                axios.post('http://localhost:8000/api/withdrawal/decline', {
                    id: id
                }).then(response => {
                    this.$buefy.toast.open({message: `Decline Success`, position: 'is-bottom'})
                    this.loadAsyncData()
                })
            }
        },
        created(){
            this.loadAsyncData();
        }
    };
</script>
