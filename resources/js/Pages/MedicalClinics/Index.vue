<template>
    <Head>
        <title>Список учреждений</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список учреждений</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header" v-if="$page.props.shared.userPermissions.includes('create_medical_clinics')">
                    <div class="card-tools">
                        <Link :href="route('medical-clinics.create')" class="btn btn-success btn-sm px-3">
                            Новое учреждение
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Название</th>
                            <th width="40"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(medicalClinic, index) in medicalClinics.data">
                            <td>{{ ((medicalClinics.meta.current_page - 1) * medicalClinics.meta.per_page) + index + 1 }}</td>
                            <td>{{ medicalClinic.name }}</td>
                            <td class="text-center" v-if="$page.props.shared.userPermissions.includes('edit_medical_clinics')">
                                <Link :href="route('medical-clinics.edit', medicalClinic.id)">
                                    <i class="fa fa-pencil-alt"></i>
                                </Link>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="medicalClinics.meta.last_page > 1">
                    <pagination :links="medicalClinics.meta.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['medicalClinics']
}
</script>
