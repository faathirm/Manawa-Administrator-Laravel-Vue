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
                    <div class="card-header ">
                        <div class="row d-flex align-items-center justify-content-start">
                            <div class="col-md-1 d-flex justify-content-end">
                                <avatar :username="this.data.fullname" backgroundColor="#2F80ED" color="#FFFF"/>
                            </div>
                            <div class="col-md-11">
                                <p class="title is-5 text-capitalize">{{ this.data.fullname }}</p>
                                <p class="subtitle is-7 text-muted"><a :href='"mailto:"+this.data.email'>{{ this.data.email }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-content p-0">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="list-group ml-3 small list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Balance</div>
                                        <div class="font-weight-bold">{{ this.data.balance }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Registered on</div>
                                        <div class="font-weight-bold">{{ this.data.registeredOn.substr(0,10).replace(/\-/g, '/') }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Livestock amount</div>
                                        <div class="font-weight-bold">{{ this.data.livestockAmount }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Transaction</div>
                                        <div class="font-weight-bold">{{ this.data.transaction }}</div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>Pending order</div>
                                        <div class="font-weight-bold">{{ this.data.pendingOrder }}</div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-8 border-left">
                                <b-tabs :animated="true">
                                    <b-tab-item label="Livestock">
                                        <div class="row mr-1">
                                            <div class="col-md-3 p-1" v-for="live in data.livestockList" :key="live.id">
                                                <router-link class="card p-0 shadow-none" tag="div" :to="'/livestock/customer/'+live.id" style="cursor: pointer;">
                                                    <div class="card-header p-0 d-flex justify-content-center has-background-dark" style="height: 10em;">
                                                        <b-icon icon="gitlab" size="is-large" class="align-self-center" type="is-white"></b-icon>
                                                    </div>
                                                    <div class="card-content p-0">
                                                        <b-progress :precision="1" show-value format="percent" :value="countPercentage(live.type,live.bornAt.substr(0,10))" type="is-success" class="m-2 mb-0" size="is-small"></b-progress>
                                                        <hr class="m-0">
                                                        <p class="title is-6 text-center p-2 mb-3 mt-3"><b-icon :icon="live.kesehatan == 'Sehat' ? 'heart' : 'cross'" :type="live.kesehatan == 'Sehat' ? 'is-danger' : 'is-dark'" size="is-small" />  {{live.animalName}} {{live.varietyName}} #{{live.id}}</p>
                                                    </div>
                                                    <footer class="card-footer p-1">
                                                        <p class="card-footer-item p-2 small">
                                                            <b-icon icon="clock-outline" size="is-small" class="pr-1 font-weight-bold" /> {{ageFormat(live.bornAt.substr(0,10))}} Mo
                                                        </p>
                                                        <p class="card-footer-item p-2 text-capitalize small">
                                                            <b-icon icon="dna" size="is-small" class="pr-1 font-weight-bold" /> {{live.type}}
                                                        </p>
                                                    </footer>
                                                </router-link>
                                            </div>
                                        </div>
                                    </b-tab-item>

                                    <b-tab-item label="Transaction" class="p-3">
                                        <b-table
                                            :paginated="true"
                                            :per-page="10"
                                            :total="this.data.transactionList.length"
                                            :data="this.data.transactionList">
                                            <template slot-scope="props">
                                                <b-table-column field="id" label="TID" sortable>
                                                    {{ props.row.id }}
                                                </b-table-column>
                                                <b-table-column class="text-capitalize" field="journal_desc" label="Description" sortable>
                                                    {{ props.row.journal_desc }}
                                                </b-table-column>
                                                <b-table-column field="value" label="Amount" sortable>
                                                    <b-tag :type="props.row.value <0 ? 'is-danger' : 'is-info'">{{ props.row.value <0 ? 'cr.' : 'dr.' }}</b-tag>
                                                    {{ Math.abs(props.row.value) | currency }}
                                                </b-table-column>
                                                <b-table-column field="created_at" label="Date" sortable>
                                                    {{ props.row.created_at}}
                                                </b-table-column>
                                            </template>
                                        </b-table>
                                    </b-tab-item>
                                </b-tabs>
                            </div>
                        </div>
                    </div>
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
            return{
                isLoading: true,
                data:{
                    id:this.$route.params.id,
                    fullname:'',
                    email:'',
                    balance:'',
                    registeredOn:'',
                    livestockAmount:'',
                    transaction:'',
                    pendingOrder:'',
                    livestockList:[],
                    transactionList:[],
                    waktuQurban: '2020-07-30'
                }
            }
        },
        methods: {
          loadAsync(){
              axios.post("http://localhost:8000/api/customer/detail",{
                  id: this.$route.params.id
              }).then(response => {
                  this.data.fullname = response.data.name;
                  this.data.email = response.data.email;
                  this.data.balance = response.data.balance;
                  this.data.registeredOn = response.data.created_at;
                  this.data.livestockAmount = response.data.livestockAmount;
                  this.data.transaction = response.data.transactionAmount;
                  this.data.pendingOrder = response.data.pendingAmount;
                  this.data.livestockList = response.data.livestocks;
                  this.data.transactionList = response.data.transactions;
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
          }
        },
        created() {
            this.isLoading = true;
            this.loadAsync();
        }
    }
</script>
