<template>
    <section v-if="isLoading">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center" style="height:20em;">
                <div class="spinner-border text-secondary align-self-center spinner-border-lg" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </section>
    <section v-else>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-none is-loading">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 row d-flex justify-content-start align-items-center">
                                <div class="col-md-2 d-flex justify-content-end">
                                    <avatar :username="this.data.farmvariety.variety.animal.name+' '+this.data.farmvariety.variety.name" backgroundColor="#2F80ED" color="#FFFF"/>
                                </div>
                                <div class="col-md-10">
                                    <p class="title is-5 text-capitalize">{{this.data.farmvariety.variety.animal.name}} {{this.data.farmvariety.variety.name}}</p>
                                    <p class="subtitle is-7 text-muted"><span class="text-capitalize">{{ this.data.farmvariety.sales_type }}</span> / <span v-if="this.data.latest_report != null"> {{this.data.latest_report.kesehatan}} <b-icon size="is-small" v-if="this.data.latest_report.kesehatan == 'Sehat'" type="is-danger" icon="heart" /> <b-icon size="is-small" v-else type="is-dark" icon="close" /> / </span> {{ageFormat(this.data.born_at.substr(0,10))}} Mo</p>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                <b-button type="is-dark" icon-left="plus" @click="addForm()"> New Report </b-button>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 pt-0 pb-0 pr-5 pl-5">
                                <b-progress :precision="1"  show-value format="percent" :value="countPercentage(this.data.farmvariety.sales_type, this.data.born_at.substr(0,10))" type="is-success" class="m-2 mb-0" size="is-small"></b-progress>
                            </div>
                        </div>
                    </div>
                    <div class="card-content p-0">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="list-group ml-3 small list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Owner</div>
                                        <div class="font-weight-bold text-capitalize"> {{this.data.customer.name}} </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Price Purchase</div>
                                        <div class="font-weight-bold">{{ this.data.price_purchase | currency }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Insurance</div>
                                        <div class="font-weight-bold">{{ this.data.price_insurance | currency }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Born at</div>
                                        <div class="font-weight-bold">{{ this.data.born_at.substr(0,10)}}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Added at</div>
                                        <div class="font-weight-bold">{{ this.data.added_at.substr(0,10) }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Sold at</div>
                                        <div class="font-weight-bold" v-if="this.data.sold_at != null">{{ this.data.sold_at.substr(0,10) }}</div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-9 border-left">
                                <b-tabs :animated="true">
                                    <b-tab-item label="Livestock Report History">
                                        <div class="row mr-1">
                                            <div class="col-md-12">
                                                <b-table
                                                    :paginated="true"
                                                    :per-page="10"
                                                    :total="this.data.report.length"
                                                    :data="this.data.report">
                                                    <template slot-scope="props">
                                                        <b-table-column field="id" label="ID" class="small" centered>
                                                            {{ props.row.id }}
                                                        </b-table-column>
                                                        <b-table-column field="weight" label="WEIGHT" class="small" centered>
                                                            {{ props.row.berat }}
                                                        </b-table-column>
                                                        <b-table-column field="kesehatan" label="STATUS" class="small" centered>
                                                            {{ props.row.kesehatan }}
                                                        </b-table-column>
                                                        <b-table-column field="report_desc" label="REPORT DESC" class="small">
                                                            {{ props.row.report_desc }}
                                                        </b-table-column>
                                                        <b-table-column field="reported_by" label="BY" class="small">
                                                            {{ props.row.admin.name }}
                                                        </b-table-column>
                                                        <b-table-column field="created_at" label="DATE" class="small">
                                                            {{ props.row.created_at }}
                                                        </b-table-column>
                                                        <b-table-column>
                                                            <span  @click="isImageModalActive = true, modalPhoto = props.row.photo_url"><b-icon icon="camera" type="is-dark" size="is-small" class="mr-2" style="cursor: pointer" /></span>
                                                            <span @click="deleteData(props.row.id)"><b-icon icon="delete" type="is-danger" size="is-small" style="cursor: pointer" /></span>
                                                        </b-table-column>
                                                    </template>
                                                </b-table>
                                            </div>
                                        </div>
                                    </b-tab-item>
                                </b-tabs>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-sidebar
            type="is-light"
            :fullheight="true"
            :right="true"
            :open.sync="open">
            <div class="has-background-dark is-bold">
                <div class="p-4">
                    <p class="title  text-white">New Report</p>
                    <p class="subtitle is-6  text-white">Enter new data</p>
                </div>
            </div>
            <div class="p-4">
                <form @submit.prevent="submitFile()">
                    <input type="hidden" v-model="id">
                    <b-field label="Weight" :label-position="'on-border'">
                        <b-input icon="weight" type="number" v-model="berat" required></b-input>
                        <p class="control">
                            <span class="button is-static">Kg</span>
                        </p>
                    </b-field>
                    <b-field label="Status" :label-position="'on-border'">
                        <b-select icon="pulse" v-model="kesehatan" name="kesehatan" required expanded>
                            <option value="Sehat">Sehat</option>
                            <option value="Mati">Mati</option>
                        </b-select>
                    </b-field>
                    <b-field label="Message" :label-position="'on-border'" class="mb-0">
                        <b-input maxlength="200" type="textarea" v-model="report_desc" required></b-input>
                    </b-field>
                    <b-upload v-model="file" expanded id="file" ref="file" v-on:change="handleFileUpload()">
                        <a class="button is-primary is-fullwidth">
                            <b-icon icon="upload"></b-icon>
                            <span>{{ file.name || "Click to upload"}}</span>
                        </a>
                    </b-upload>
                    <button class="btn btn-success btn-block">Submit</button>
                </form>
            </div>
        </b-sidebar>
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
            return{
                isLoading: true,
                data:null,
                id:'',
                berat:'',
                status:'',
                report_desc:'',
                kesehatan:'',
                file: {},
                //Sidebar
                open: false,
                //Modal
                isImageModalActive: false,
                modalPhoto:''
            }
        },
        methods: {
            loadAsync(){
                axios.post("http://manawa.akugap.tech/api/customerlivestock/detail",{
                    id: this.$route.params.id
                }).then(response => {
                    this.data = response.data[0];
                    this.isLoading = false;
                });
            },
            ageFormat(date){
                return Math.abs(Vue.moment(date).diff(Date(), 'month'));
            },
            countPercentage(type, date){
                if(type == 'regular'){
                    var total = (Math.abs(Vue.moment(date).diff(Date(), 'month')) / 12)*100;
                    return total;
                }else{
                    var bedaWaktu = (Math.abs(Vue.moment(date).diff(this.waktuQurban, 'month')));
                    var waktuJual = ((Vue.moment(this.waktuQurban).format("YYYY") - Vue.moment(date).format("YYYY"))*12) + (Vue.moment(this.waktuQurban).format("M") - Vue.moment(date).format("M"));
                    var total = (Math.abs(Vue.moment(date).diff(Date(), 'month')) / waktuJual)*100;
                    return total;
                }
            },
            addForm(){
                this.open = true;
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            submitFile(){
                let formData = new FormData();
                formData.append('id',this.$route.params.id);
                formData.append('berat',this.berat);
                formData.append('status',this.status);
                formData.append('kesehatan',this.kesehatan);
                formData.append('report_desc',this.report_desc);
                if(this.file.name){
                    formData.append('file',this.file);
                    axios.post('http://manawa.akugap.tech/api/customerlivestock/report', formData, {headers: {'Content-Type': 'multipart/form-data'}
                    }).then(response => {
                        this.file = {};
                        this.berat = '';
                        this.status = '';
                        this.report_desc = '';
                        this.kesehatan = '';
                        this.$buefy.toast.open({message: `Upload Success`, position: 'is-bottom'});
                        this.isLoading = true;
                        this.loadAsync();
                        this.open = false;
                    }).catch(function(){
                        this.$buefy.toast.open({message: `Upload Failed`, position: 'is-bottom', type: 'is-danger'});
                    });
                }else{
                    this.$buefy.toast.open({message: `Upload Failed`, position: 'is-bottom', type: 'is-danger'});
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
                        axios.post('http://manawa.akugap.tech/api/customerlivestock/reportdelete', {
                            result: id
                        }).then(response => {
                            this.$buefy.toast.open({message: `Delete Success`, position: 'is-bottom'})
                            this.loadAsync()
                        })
                    }
                })
            }
        },
        created() {
            this.isLoading = true;
            this.loadAsync();
        }
    }
</script>
