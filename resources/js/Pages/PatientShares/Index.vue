<template>
    <Head>
        <title>Поделились со мной</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 col-sm-6">
                    <h1 class="m-0">Поделились со мной</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <input type="text"
                           v-model="search.query"
                           @blur="doSearch"
                           @keydown.enter="doSearch"
                           class="form-control form-control-sm"
                           placeholder="Имя пациента"/>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Ф.И.О</th>
                                <th>Ответов</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr :class="{'table-warning': patient.current_user_consultations_count === 0}" v-for="patient in patients">
                                <td>
                                    <Link :href="route('patients.show', patient.id)">{{ patient.name }}</Link>
                                </td>
                                <td>
                                    <Link :href="route('patients.show', patient.id)"><i class="fa fa-comments"></i>
                                        {{patient.current_user_consultations_count}}
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, router} from "@inertiajs/vue3";
import Pagination from "../../Shared/Pagination.vue";
import pickBy from 'lodash/pickBy'

export default {
    components: {Pagination, Head, Link},
    props: ['filterParams', 'patients'],
    data() {
        return {
            search: {
                query: this.filterParams?.query || null
            },
        }
    },
    methods: {
        doSearch() {
            this.$inertia.get(route('patient-shares.index'), pickBy(this.search), {preserveState: true})
        }
    },
}
</script>
