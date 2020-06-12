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
                            <b-table-column field="name" label="NAME" cellClass="text-capitalize" sortable>
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.variety.animal.name+' '+props.row.variety.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.variety.animal.name+' '+props.row.variety.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.variety.animal.name+' '+props.row.variety.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.variety.animal.name+' '+props.row.variety.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="sales_type" label="TYPE" cellClass="text-capitalize" sortable>
                                {{ props.row.sales_type}}
                            </b-table-column>
                            <b-table-column field="price_base" label="CURRENT PRICE" cellClass="text-capitalize" sortable>
                                {{getCurrentPrice(props.row.sales_type, props.row.price_base, props.row.price_monthly_incr) | currency}}
                            </b-table-column>
                            <b-table-column field="stock" label="STOCK" cellClass="text-capitalize" centered>
                                {{ props.row.stock}}
                            </b-table-column>
                            <b-table-column field="created_at" label="CREATED AT" cellClass="text-capitalize" sortable>
                                {{ props.row.created_at }}
                            </b-table-column>
                        </template>
                        <template slot="detail" slot-scope="props">
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <img class="img-fluid img-thumbnail" :src="props.row.photo_url">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <p>
                                                            <span class="title is-4">{{ props.row.variety.animal.name+' '+props.row.variety.name }}</span><br>
                                                            {{props.row.farm.name}} - <span class="text-capitalize">{{props.row.sales_type}}</span><br>
                                                            <hr class="m-1"/>
                                                            {{props.row.variety_desc}}
                                                        </p>
                                                        <b-field grouped group-multiline>
                                                            <div class="control">
                                                                <b-taglist attached>
                                                                    <b-tag type="is-dark">Price Base</b-tag>
                                                                    <b-tag type="is-info">{{props.row.price_base | currency}}</b-tag>
                                                                </b-taglist>
                                                            </div>
                                                            <div class="control" v-if="props.row.sales_type == 'qurban'">
                                                                <b-taglist attached>
                                                                    <b-tag type="is-dark">Price Increment</b-tag>
                                                                    <b-tag type="is-info">{{props.row.price_monthly_incr | currency}}</b-tag>
                                                                </b-taglist>
                                                            </div>
                                                            <div class="control">
                                                                <b-taglist attached>
                                                                    <b-tag type="is-dark">Price Insurance</b-tag>
                                                                    <b-tag type="is-info">{{props.row.price_insurance | currency}}</b-tag>
                                                                </b-taglist>
                                                            </div>
                                                            <div class="control">
                                                                <b-taglist attached>
                                                                    <b-tag type="is-dark">Price Estimated Sell</b-tag>
                                                                    <b-tag type="is-info">{{props.row.price_est_sell | currency}}</b-tag>
                                                                </b-taglist>
                                                            </div>
                                                    </b-field>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex justify-content-end">
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
                            <th class="is-hidden-mobile"> <div class="th-wrap">NAME</div> </th>
                            <th class="is-hidden-mobile"> <div class="th-wrap">TYPE</div> </th>
                            <th class="is-hidden-mobile"> <div class="th-wrap">PRICE BASE</div> </th>
                            <th class="is-hidden-mobile"><div class="th-wrap is-centered">STOCK</div></th>
                            <th class="is-hidden-mobile"> <div class="th-wrap">CREATED AT</div> </th>
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
                <form @submit.prevent="addData()" content-type="multipart/form-data">
                    <b-field label="Farm" :label-position="'on-border'">
                        <b-select v-model="form.farm" expanded required>
                            <option v-for="list in this.allFarm" :value="list.id">{{list.name}}</option>
                        </b-select>
                    </b-field>
                    <b-field label="Variety" :label-position="'on-border'">
                        <b-select v-model="form.variety" expanded required>
                            <option v-for="list in this.allVariety" :value="list.id">{{list.animal.name+' '+list.name}}</option>
                        </b-select>
                    </b-field>
                    <b-field label="Sales Type" :label-position="'on-border'">
                        <b-select v-model="form.sales_type" expanded required>
                            <option value="regular">Regular</option>
                            <option value="qurban">Qurban</option>
                        </b-select>
                    </b-field>
                    <b-field label="Price Base" :label-position="'on-border'">
                        <b-input v-cleave="{ numeral: true, numeralThousandsGroupStyle: 'thousand'}" v-model="form.price_base" required></b-input>
                    </b-field>
                    <b-field label="Price Monthly Increment" :label-position="'on-border'">
                        <b-input v-cleave="{ numeral: true, numeralThousandsGroupStyle: 'thousand'}" v-model="form.price_monthly_incr" required></b-input>
                    </b-field>
                    <b-field label="Price Insurance" :label-position="'on-border'">
                        <b-input v-cleave="{ numeral: true, numeralThousandsGroupStyle: 'thousand'}" v-model="form.price_insurance" required></b-input>
                    </b-field>
                    <b-field label="Price Estimated Sell" :label-position="'on-border'">
                        <b-input v-cleave="{ numeral: true, numeralThousandsGroupStyle: 'thousand'}" v-model="form.price_est_sell" required></b-input>
                    </b-field>
                    <b-field label="Stock" :label-position="'on-border'">
                        <b-numberinput v-model="form.stock"></b-numberinput>
                    </b-field>
                    <b-field label="Message" :label-position="'on-border'" class="mb-0">
                        <b-input maxlength="200" v-model="form.variety_desc" type="textarea"></b-input>
                    </b-field>
                    <b-field class="file">
                        <b-upload v-model="form.photo_url" expanded>
                            <a class="button is-dark is-fullwidth">
                                <b-icon icon="camera"></b-icon>
                                <span>{{ form.photo_url.name || "Click to upload"}}</span>
                            </a>
                        </b-upload>
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
                    farm:'',
                    variety:'',
                    sales_type:'',
                    price_base:0,
                    price_monthly_incr:0,
                    price_est_sell:0,
                    price_insurance:0,
                    variety_desc:'',
                    photo_url:{},
                    thumbnail_url:'',
                    stock:0
                },
                //DetailedTabel
                defaultOpenedDetails: [1],
                showDetailIcon: true,
                //Data
                allAnimal: [],
                waktuQurban: '2020-07-30',
                umurTigaBulan: '2019-10-10',
                allFarm:[],
                allVariety:[],
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
                axios.get(`http://localhost:8000/api/farmvariety?${params}`).then(response => {
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
                this.form.farm=''
                this.form.variety=''
                this.form.sales_type=''
                this.form.price_base=0
                this.form.price_monthly_incr=0
                this.form.price_est_sell=0
                this.form.price_insurance=0
                this.form.variety_desc=''
                this.form.photo_url={}
                this.form.thumbnail_url=''
                this.form.stock=0
            },
            addForm(){
                this.isEdit = false
                this.open = true
            },
            editForm(data){
                console.log(data);
                this.currentId = data.id;
                this.form.farm= data.id_farm;
                this.form.variety= data.id_variety;
                this.form.sales_type=data.sales_type;
                this.form.price_base=data.price_base;
                this.form.price_monthly_incr=data.price_monthly_incr;
                this.form.price_est_sell=data.price_est_sell;
                this.form.variety_desc=data.variety_desc;
                // this.form.photo_url=data.photo_url;
                // this.form.thumbnail_url=data.thumbnail_url;
                this.form.stock=data.stock;
                console.log(this.form);
                this.isEdit = true;
                this.open = true;
            },
            addData(){
                if(this.isEdit){
                    axios.post('http://localhost:8000/api/variety/update', {
                        result: this.form,
                        id: this.currentId
                    }).then(response => {
                        this.clearField()
                        this.open = false;
                        this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                        this.loadAsyncData();
                    })
                    //INSERT
                }else{
                    axios.post('http://localhost:8000/api/farmvariety', {
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
                    confirmText: 'Delete Account',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        axios.post('http://localhost:8000/api/variety/delete', {
                            result: id
                        }).then(response => {
                            this.$buefy.toast.open({message: `Delete Success`, position: 'is-bottom'})
                            this.loadAsyncData()
                        })
                    }
                })
            },
            getAllAnimal() {
                axios.get('http://localhost:8000/api/variety/animal').then(response => {
                    this.allAnimal = response.data;
                });
            },
            getCurrentPrice(type, price_base, price_montly_incr){
                if(type == 'regular'){
                    return price_base;
                }else{
                    var test = Math.abs(Vue.moment(Date()).diff(this.umurTigaBulan, 'month'));
                    var jumlahIncrement = price_montly_incr * test;
                    var data = (price_base + jumlahIncrement);
                    return data;
                }
            },
            getFarm() {
                axios.get('http://localhost:8000/api/farmvariety/farm').then(response => {
                    this.allFarm = response.data;
                });
            },
            getVariety() {
                axios.get('http://localhost:8000/api/farmvariety/variety').then(response => {
                    this.allVariety = response.data;
                });
            }
        },
        created(){
            this.getFarm();
            this.getVariety();
            this.loadAsyncData();
        }
    };
</script>
