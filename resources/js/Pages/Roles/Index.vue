<template>
    <Head>
        <title>Список ролей</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список ролей</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header" v-if="$page.props.shared.userPermissions.includes('create_roles')">
                    <div class="card-tools">
                        <Link :href="route('roles.create')" class="btn btn-success btn-sm px-3">
                            Новая роль
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
                        <tr v-for="(role, index) in roles.data">
                            <td>{{ ((roles.meta.current_page - 1) * roles.meta.per_page) + index + 1 }}</td>
                            <td>{{ role.readable_name }}</td>
                            <td class="text-center" v-if="$page.props.shared.userPermissions.includes('edit_roles')">
                                <Link :href="route('roles.edit', role.id)">
                                    <i class="fa fa-pencil-alt"></i>
                                </Link>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="roles.meta.last_page > 1">
                    <pagination :links="roles.meta.links"/>
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
    props: ['roles']
}
</script>
