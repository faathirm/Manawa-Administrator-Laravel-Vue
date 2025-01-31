<template>
    <section>
        <div class="p-4">
            <div class="row d-flex justify-content-between">
                <div class="col-md-4">
                    <b-button type="is-dark" icon-left="plus" @click="addForm(), clearField()"> New Entry </b-button>
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
                            <b-table-column field="name" label="NAME" cellClass="text-capitalize" sortable>
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="email" label="EMAIL" sortable>
                                {{ props.row.email }}
                            </b-table-column>
                            <b-table-column field="created_at" label="REGISTERED AT" cellClass="text-capitalize" sortable>
                                {{ props.row.created_at }}
                            </b-table-column>
                        </template>
                        <template slot="detail" slot-scope="props">
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-md-6">
                                                <p><strong class="text-capitalize">{{props.row.name}}</strong><br>{{props.row.email}}</p>
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
                        <template slot="footer">
                            <th class="is-hidden-mobile"/>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">NAME</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">EMAIL</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">REGISTERED AT</div>
                            </th>
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
                    <b-field label="Name" :label-position="'on-border'">
                        <b-input icon="account" v-model="form.formName" required></b-input>
                    </b-field>
                    <b-field label="Email" :label-position="'on-border'">
                        <b-input type="email" icon="email" v-model="form.formEmail" required></b-input>
                    </b-field>
                    <b-field label="Password" :type="{'is-danger': notMatch }" :label-position="'on-border'">
                        <b-input type="password" icon="fingerprint" minlength="6" v-model="form.formPassword" required></b-input>
                    </b-field>
                    <b-field label="Confirm Password" :type="{'is-danger': notMatch }" :message="[{'Password is not match': notMatch}]"  :label-position="'on-border'">
                        <b-input type="password" icon="fingerprint" minlength="6" v-model="form.formPasswordConfirm" required></b-input>
                    </b-field>
                    <button class="btn btn-success btn-block">Submit</button>
                </form>
            </div>

        </b-sidebar>
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
                //Sidebar
                open: false,
                isEdit: false,
                //State
                notMatch: false,
                currentId: 0,
                //Form
                form:{
                    formName:'',
                    formEmail:'',
                    formPassword:'',
                    formPasswordConfirm:'',
                },
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
                axios.get(`http://manawa.akugap.tech/api/admin?${params}`).then(response => {
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
            passwordMatch(){
                if(this.form.formPassword == this.form.formPasswordConfirm){
                    this.notMatch = false;
                    return true;
                }else{
                    this.notMatch = true;
                    return false;
                }
            },
            clearField(){
                this.form.formName = '';
                this.form.formEmail = '';
                this.form.formPassword = '';
                this.form.formPasswordConfirm = '';
            },
            addForm(){
              this.isEdit = false
              this.open = true
            },
            editForm(data){
                this.currentId = data.id
                this.form.formName = data.name
                this.form.formEmail = data.email
                this.isEdit = true
                this.open = true
            },
            addData(){
                if(this.passwordMatch()){
                    //UPDATE
                    if(this.isEdit){
                        axios.post('http://manawa.akugap.tech/api/admin/update', {
                            result: this.form,
                            id: this.currentId
                        }).then(response => {
                            this.clearField()
                            this.open = false;
                            this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                            this.loadAsyncData()
                        })
                    //INSERT
                    }else{
                        axios.post('http://manawa.akugap.tech/api/admin', {
                            result: this.form
                        }).then(response => {
                            this.clearField()
                            this.open = false;
                            this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                            this.loadAsyncData()
                        })
                    }
                }
            },
            deleteData(id){
                this.$buefy.dialog.confirm({
                    title: 'Deleting data',
                    message: 'Are you sure you want to <b>delete</b> this data? This action cannot be undone.',
                    confirmText: 'Delete Account',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        axios.post('http://manawa.akugap.tech/api/admin/delete', {
                            result: id
                        }).then(response => {
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
