<template>
    <Head>
        <title>Список пациентов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 col-sm-6">
                    <h1 class="m-0">Список пациентов</h1>
                </div>

                <div class="col-12 col-sm-6 text-left text-sm-right mt-3 mt-sm-0">
                    <Link class="btn btn-outline-primary" :href="route('patients.full_records')">
                        <i class="fa fa-list"></i> Все пациенты
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <input type="text"
                                   v-model="search.query"
                                   @blur="doSearch"
                                   @keydown.enter="doSearch"
                                   class="form-control form-control-sm"
                                   placeholder="Имя пациента"/>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 col-12 mt-3 mt-sm-0 text-right">
                            <Link :href="route('patients.create')" class="btn btn-success btn-sm px-3">
                                Новый пациент
                            </Link>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Ф.И.О</th>
                                <th width="200">Дата добавления</th>
                                <th width="80"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="patient in patients.data">
                                <td>
                                    <Link :href="route('patients.show', patient.id)">{{ patient.name }}</Link>
                                </td>
                                <td>{{patient.created_at}}</td>
                                <td class="text-center">
                                    <Link :href="route('patients.edit', patient.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="patients.meta.last_page > 1">
                    <pagination :links="patients.meta.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "../../Shared/Pagination.vue";
import pickBy from 'lodash/pickBy'

export default {
    components: {Pagination, Head, Link},
    props: ['patients'],
    data: () => ({
        search: {
            query: ''
        },
    }),
    methods: {
        doSearch() {
            this.$inertia.get('/patients', pickBy(this.search), {preserveState: true})
        }
    },
}
</script>
