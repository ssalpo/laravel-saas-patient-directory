<template>
    <Head>
        <title>Список пациентов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список пациентов</h1>
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
                                   placeholder="Ф.И.О, код"/>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 col-12 mt-3 mt-sm-0 text-right"
                             v-if="$page.props.shared.userPermissions.includes('create_patients')">
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
                                <th>Номер кейса</th>
                                <th>Ф.И.О</th>
                                <th>Статус</th>
                                <th v-if="$page.props.shared.userPermissions.includes('edit_patients')"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(patient, index) in patients.data">
                                <td>{{ patient.case_numbers }}</td>
                                <td>
                                    <Link :href="route('patients.show', patient.id)">{{ patient.name }}</Link>
                                </td>
                                <td :class="[patient.status === 1 ? 'text-danger' : 'text-success']">
                                    {{ patient.status == 1 ? 'На проверке' : 'Проверено' }}
                                </td>
                                <td v-if="$page.props.shared.userPermissions.includes('edit_patients')"
                                    class="text-center">
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

                <div class="card-footer clearfix" v-if="patients.links.length > 3">
                    <pagination :links="patients.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "../../Shared/Pagination.vue";
import debounce from 'lodash/debounce'
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
