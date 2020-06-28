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
                                <b-icon v-if="props.row.latest_report != null && props.row.latest_report.kesehatan == 'Sehat'" icon="heart" type="is-danger" size="is-small" />
                                <b-icon v-else-if="props.row.latest_report != null && props.row.latest_report.kesehatan == 'Mati'" icon="close-thick" type="is-dark" size="is-small" />
                                <b-icon v-else icon="help" type="is-dark" size="is-small" />
                                {{ props.row.id }}
                            </b-table-column>
                            <b-table-column field="customer.name" label="NAME" cellClass="text-capitalize">
                                <template v-if="showDetailIcon">
                                    <a @click="toggle(props.row)">
                                        <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                    </a>
                                </template>
                                <template v-else>
                                    <avatar :username="props.row.customer.name" :size="24" backgroundColor="#e74c3c" color="white" :inline="true"></avatar> {{ props.row.customer.name }}
                                </template>
                            </b-table-column>
                            <b-table-column field="id_user" label="PRODUCT" cellClass="text-capitalize">
                                {{ props.row.farmvariety.variety.animal.name }} {{ props.row.farmvariety.variety.name }}
                            </b-table-column>
                            <b-table-column field="born_at" label="AGE" cellClass="text-capitalize" sortable>
                                {{ ageFormat(props.row.born_at) }} Mo
                            </b-table-column>
                            <b-table-column field="created_at" label="LATEST REPORT" cellClass="text-capitalize">
                                <b-tag v-if="props.row.latest_report != null && checkMonth(props.row.latest_report.created_at) == false" size="is-medium" type="is-danger" expanded >{{ props.row.latest_report.created_at.substr(0,10) }}</b-tag>
                                <b-tag v-else-if="props.row.latest_report != null && checkMonth(props.row.latest_report.created_at) == true" size="is-medium" expanded >{{ props.row.latest_report.created_at.substr(0,10) }}</b-tag>
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
                                                    <div class="col-md-4 d-flex justify-content-center " v-if="props.row.latest_report != null" >
                                                        <img @click="isImageModalActive = true, modalPhoto = props.row.latest_report.photo_url" class="img-thumbnail" style="max-height:250px;" :src="props.row.latest_report.photo_url">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <p>
                                                            <span class="title is-4">{{ props.row.farmvariety.variety.animal.name+' '+props.row.farmvariety.variety.name }}</span><br>
                                                            <span class="text-capitalize">{{props.row.customer.name}} - {{props.row.farmvariety.sales_type}} / {{ageFormat(props.row.born_at.substr(0,10))}} Mo</span><br>
                                                        <hr class="m-1"/>
                                                        </p>
                                                        <ul class="list-group list-group-flush m-0 small">
                                                            <li class="list-group-item d-flex justify-content-between bg-transparent" v-if="props.row.latest_report != null"><span>Latest Report</span><span>{{props.row.latest_report.created_at.substr(0,10)}}</span></li>
                                                            <li class="list-group-item d-flex justify-content-between bg-transparent" v-if="props.row.latest_report != null"><span>Latest Condition</span><span>{{props.row.latest_report.kesehatan}}</span></li>
                                                            <li class="list-group-item d-flex justify-content-between bg-transparent"><span>Price Purchase</span><span>{{props.row.price_purchase | currency}}</span></li>
                                                            <li class="list-group-item d-flex justify-content-between bg-transparent"><span>Price Insurance</span><span>{{props.row.price_insurance | currency}}</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex justify-content-end">
                                                <div class="buttons has-addons">
                                                    <b-button type="is-dark" icon-left="account-details" class="text-decoration-none" tag="router-link" :to="'/backend/livestock/customer/'+props.row.id" outlined>Detail</b-button>
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
                                <div class="th-wrap">TID</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">NAME</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">PRODUCT</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">AGE</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">LATEST REPORT</div>
                            </th>
                            <th class="is-hidden-mobile">
                                <div class="th-wrap">CREATED AT</div>
                            </th>
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
                //Form
                form:{
                    formName:'',
                },
                //DetailedTabel
                defaultOpenedDetails: [1],
                showDetailIcon: true,
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
                axios.get(`http://manawa.akugap.tech/api/customerlivestock?${params}`).then(response => {
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
            ageFormat(date){
                if(date){
                    return Math.abs(Vue.moment(date.substr(0,10)).diff(Date(), 'month'));
                }else{
                    return 0;
                }

            },
            checkMonth(date){
                var d1 = Vue.moment(date.substr(0,10));
                var d2 = Vue.moment(Date());
                if(d1.month() == d2.month() && d1.year() == d2.year()){
                    return true;
                }else{
                    return false;
                }
            }
        },
        created(){
            this.loadAsyncData();
        }
    };
</script>
