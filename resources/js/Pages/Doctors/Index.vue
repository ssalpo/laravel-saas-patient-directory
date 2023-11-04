<template>
    <Head>
        <title>Список врачей</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список врачей</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header" v-if="$page.props.shared.userPermissions.includes('create_doctors')">
                    <div class="card-tools">
                        <Link :href="route('doctors.create')" class="btn btn-success btn-sm px-3">
                            Новый врач
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div v-if="$page.props.flash.message" class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $page.props.flash.message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Имя</th>
                            <th class="text-center" width="200">Кол-во. пациентов</th>
                            <th>Номер телефона</th>
                            <th width="80"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(doctor, index) in doctors.data">
                            <td>{{ ((doctors.current_page - 1) * doctors.per_page) + index + 1 }}</td>
                            <td>{{ doctor.name }}</td>
                            <td class="text-center">
                                <Link :href="route('doctors.patients', doctor.id)">{{ doctor.patients_count }}</Link>
                            </td>
                            <td>{{ doctor.phone }}</td>
                            <td class="text-center" v-if="$page.props.shared.userPermissions.includes('edit_doctors')">
                                <Link :href="route('doctors.edit', doctor.id)" class="mr-3">
                                    <i class="fa fa-pencil-alt"></i>
                                </Link>

                                <Link :href="route('doctors.edit', doctor.id)">

                                </Link>

                                <a href="#" @click.prevent="deleteDoctor(doctor.id)">
                                    <i class="fa fa-trash text-red"></i>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="doctors.links.length > 3">
                    <pagination :links="doctors.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['doctors'],
    methods: {
        deleteDoctor(id) {
            if(!confirm('Вы уверены что хотите удалить?')) return;

            this.$inertia.delete(route('doctors.destroy', id))
        }
    }
}
</script>
