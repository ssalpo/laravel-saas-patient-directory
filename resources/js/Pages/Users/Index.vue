<template>
    <Head>
        <title>Список пользователей</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список пользователей</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header" v-if="$page.props.shared.userPermissions.includes('create_users')">
                    <div class="card-tools">
                        <Link :href="route('users.create')" class="btn btn-success btn-sm px-3">
                            Новый пользователь
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
                                <th>Имя</th>
                                <th>Логин</th>
                                <th>Дата создания</th>
                                <th>Роль</th>
                                <th class="text-center">К-во п.</th>
                                <th class="text-center">Специальность</th>
                                <th class="text-center">Активность</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user, index) in users.data">
                                <td>{{ ((users.meta.current_page - 1) * users.meta.per_page) + index + 1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.username }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>{{ user.roles.map(e => e.readable_name).join(', ') }}</td>
                                <td class="text-center">{{ user.patients_count }}</td>
                                <td class="text-center">{{user.speciality?.name || '-'}}</td>
                                <td class="text-center">
                                    <input type="checkbox" :checked="user.is_active" @change="$page.props.shared.userPermissions.includes('toggle_activity_users') ? toggleActivity(user.id) : null">
                                </td>
                                <td class="text-center"
                                    v-if="$page.props.shared.userPermissions.includes('edit_users')">
                                    <Link :href="route('users.edit', user.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="users.meta.last_page > 1">
                    <pagination :links="users.meta.links"/>
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
    props: ['users'],
    methods: {
        toggleActivity(id) {
            this.$inertia.post(route('users.toggle_activity', id));
        }
    }
}
</script>
