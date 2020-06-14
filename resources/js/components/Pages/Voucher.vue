<template>
    <section>
        <div>
            <div class="row d-flex justify-content-between">
                <div class="col-md-4">
                    <b-button type="is-dark" icon-left="plus" @click="addForm()"> New Entry </b-button>
                </div>
                <div class="col-md-4">
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
                            <b-table-column field="id_user" label="NAME" cellClass="text-capitalize" sortable>
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)" v-if="props.row.customer != null">
                                        <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                    </a>
                                    <a @click="toggle(props.row)" v-else>
                                        <avatar username="E V" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> Everyone
                                    </a>
                                </template>
                                <template v-else-if="props.row.customer != null">
                                    <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                </template>
                                <template v-else>
                                    <avatar username="E V" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> Everyone
                                </template>
                            </b-table-column>
                            <b-table-column field="code" label="CODE" cellClass="text-uppercase" sortable>
                                {{ props.row.code }}
                            </b-table-column>
                            <b-table-column field="description" label="DESCRIPTION" cellClass="text-capitalize">
                                <template v-if="props.row.description != null">{{ props.row.description }}</template>
                                <template v-else>-</template>
                            </b-table-column>
                            <b-table-column field="amount" label="AMOUNT" cellClass="text-capitalize" centered sortable>
                                {{ props.row.amount | currency }}
                            </b-table-column>
                            <b-table-column field="max_usage" label="MAX USAGE" cellClass="text-capitalize" centered sortable>
                                {{ props.row.max_usage }}
                            </b-table-column>
                            <b-table-column field="used" label="USED" cellClass="text-capitalize" centered sortable>
                                {{ props.row.used }}
                            </b-table-column>
                            <b-table-column field="available_at" label="AVAILABLE AT" cellClass="text-capitalize" sortable>
                                {{ props.row.available_at }}
                            </b-table-column>
                            <b-table-column field="expired_at" label="EXPIRED AT" cellClass="text-capitalize" sortable>
                                {{ props.row.expired_at }}
                            </b-table-column>
                        </template>
                        <template slot="detail" slot-scope="props">
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-md-6">
                                                <p><strong class="text-capitalize">{{props.row.code}}</strong><br>{{props.row.created_at}}</p>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end">
                                                <div class="buttons has-addons">
                                                    <b-button type="is-dark" icon-left="pencil" @click="editForm(props.row)" outlined>Edit</b-button>
                                                    <b-button type="is-dark" icon-left="delete" @click="deleteData(props.row.id)" outlined>Delete</b-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
        <b-sidebar
            type="is-light"
            :fullheight="true"
            :right="true"
            :open.sync="open">
            <div class="has-background-dark is-bold">
                <template v-if="isEdit">
                    <div class="p-4">
                        <p class="title  text-white">Edit Entry</p>
                        <p class="subtitle is-6  text-white">Edit entry data</p>
                    </div>
                </template>
                <template v-else>
                    <div class="p-4">
                        <p class="title  text-white">New Entry</p>
                        <p class="subtitle is-6  text-white">Enter new data</p>
                    </div>
                </template>
            </div>
            <div class="p-4">
                <form @submit.prevent="addData()">
                    <b-field label="Code" :label-position="'on-border'">
                        <b-input icon="ticket-percent" v-model="form.formCode" required></b-input>
                    </b-field>
                    <b-field label="Description" :label-position="'on-border'">
                        <b-input maxlength="200" v-model="form.formDescription" type="textarea" required></b-input>
                    </b-field>
                    <b-field label="Amount" :label-position="'on-border'">
                        <b-input v-cleave="{ numeral: true, numeralThousandsGroupStyle: 'thousand'}" v-model="form.formAmount" required></b-input>
                    </b-field>
                    <b-field label="Max Usage" :label-position="'on-border'">
                        <b-input type="number" icon="ticket-confirmation" v-model="form.formMaxUsage" required></b-input>
                    </b-field>
                    <b-field label="Available At" :label-position="'on-border'">
                        <b-datetimepicker
                            v-model="form.formAvailableAt"
                            placeholder="Available At"
                            icon="calendar-today"
                            :timepicker="{ enableSeconds: true}" required>
                        </b-datetimepicker>
                    </b-field>
                    <b-field label="Expired At" :label-position="'on-border'">
                        <b-datetimepicker
                            v-model="form.formExpiredAt"
                            placeholder="Expired At"
                            icon="calendar-today"
                            :timepicker="{ enableSeconds: true}" required>
                        </b-datetimepicker>
                    </b-field>
                    <button class="btn btn-success btn-block">Submit</button>
                </form>
            </div>
        </b-sidebar>
    </section>
</template>
<script>
    import {Avatar} from "vue-avatar";
    import VueCleaveDirective from 'vue-cleave-directive'

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
                //Sidebar
                open: false,
                isEdit: false,
                //State
                notMatch: false,
                currentId: 0,
                //Form
                form:{
                    formCode:'',
                    formDescription:'',
                    formAmount:'',
                    formMaxUsage:'',
                    formAvailableAt:null,
                    formExpiredAt:null,
                },
                //DetailedTabel
                defaultOpenedDetails: [1],
                showDetailIcon: true

            };
        },
        directives: {
            cleave: VueCleaveDirective.directive
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
                // console.log(`http://manawa.akugap.tech/api/admin?${params}`);
                axios.get(`http://manawa.akugap.tech/api/voucher?${params}`).then(response => {
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
            clearField(){
                this.form.formName = '';
                this.form.formCode = '';
                this.form.formDescription = '';
                this.form.formAmount = '';
                this.form.formMaxUsage = '';
                this.form.formAvailableAt = null;
                this.form.formExpiredAt = null;
            },
            addForm(){
                this.clearField();
                this.isEdit = false
                this.open = true
            },
            editForm(data){
                this.clearField();
                console.log(data)
                this.currentId = data.id;
                this.form.formName = data.name;
                this.form.formCode = data.code;
                this.form.formDescription = data.description;
                this.form.formAmount = data.amount;
                this.form.formMaxUsage = data.max_usage;
                this.form.formAvailableAt = data.available_at;
                this.form.formExpiredAt = data.expired_at;
                this.isEdit = true;
                this.open = true;
            },
            addData(){
                if(this.isEdit){
                    axios.post('http://manawa.akugap.tech/api/voucher/update', {
                        result: this.form,
                        id: this.currentId
                    }).then(response => {
                        console.log(response)
                        this.clearField()
                        this.open = false;
                        this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                        this.loadAsyncData();
                    })
                    //INSERT
                }else{
                    axios.post('http://manawa.akugap.tech/api/voucher', {
                        result: this.form
                    }).then(response => {
                        console.log(response)
                        this.clearField()
                        this.open = false;
                        this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                        this.loadAsyncData();
                    })
                }
            },
            deleteData(id){
                this.$buefy.dialog.confirm({
                    title: 'Deleting data',
                    message: 'Are you sure you want to <b>delete</b> this data? This action cannot be undone.',
                    confirmText: 'Delete Data',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        axios.post('http://manawa.akugap.tech/api/voucher/delete', {
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
