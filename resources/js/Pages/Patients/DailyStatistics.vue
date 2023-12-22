<template>
    <Head>
        <title>Дневная статистика</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Дневная статистика</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Кол-во</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="statistic in statistics">
                                <td>{{ statistic.s_date }}</td>
                                <td>{{ statistic.total }}</td>
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
import {Head, Link} from "@inertiajs/vue3";

export default {
    components: {Head, Link},
    props: ['statistics']
}
</script>
