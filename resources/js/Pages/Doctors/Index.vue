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
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">ID</th>
                            <th>Имя</th>
                            <th width="40"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="doctor in doctors.data">
                            <td>{{ doctor.id }}</td>
                            <td>{{ doctor.name }}</td>
                            <td class="text-center" v-if="$page.props.shared.userPermissions.includes('edit_doctors')">
                                <Link :href="route('doctors.edit', doctor.id)">
                                    <i class="fa fa-pencil-alt"></i>
                                </Link>
                            </td>
                        </tr>

                        </tbody>
                    </table>
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
    props: ['doctors']
}
</script>
