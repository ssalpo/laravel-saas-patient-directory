<template>
    <Head>
        <title>Список локаций</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список локаций</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('locations.create')" class="btn btn-success btn-sm px-3">
                            Новая локация
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
                            <th>Область</th>
                            <th>Город/Район</th>
                            <th width="40"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(medicalClinic, index) in locations.data">
                            <td>{{ ((locations.meta.current_page - 1) * locations.meta.per_page) + index + 1 }}</td>
                            <td>{{ medicalClinic.region }}</td>
                            <td>{{ medicalClinic.area }}</td>
                            <td class="text-center">
                                <Link :href="route('locations.edit', medicalClinic.id)">
                                    <i class="fa fa-pencil-alt"></i>
                                </Link>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="locations.meta.last_page > 1">
                    <pagination :links="locations.meta.links"/>
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
    props: ['locations']
}
</script>
