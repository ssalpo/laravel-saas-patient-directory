<template>
    <el-menu
        class="el-menu-demo"
        mode="horizontal"
        background-color="#545c64"
        text-color="#fff"
        active-text-color="#ffd04b"
        :default-active="`${activeIndex}`"
    >
        <el-menu-item :index="`${i}`" :key="`${i}`"
                      v-for="(item, i) in parentItems"
                      @click="() => $inertia.visit(item.route)"
        >
            {{ item.title }}
        </el-menu-item>

        <el-sub-menu :index="`${i}`"
                     :key="`${i}`"
                     v-for="(item, i) in parentWithChilds"
        >
            <template #title>{{ item.title }}</template>

            <el-menu-item
                :index="`${i}-${chI}`"
                :key="`${i}-${chI}`"
                @click="() => $inertia.visit(child.route)"
                v-for="(child, chI) in item.childs"
            >
                {{ child.title }}
            </el-menu-item>

        </el-sub-menu>
    </el-menu>
</template>

<script>
import {Link, router} from "@inertiajs/vue3";
import {ElMenu, ElSubMenu, ElMenuItem} from "element-plus";
import PermissionCheck from "../Mixins/PermissionCheck";

export default {
    components: {Link, ElMenu, ElSubMenu, ElMenuItem},
    mixins: [PermissionCheck],
    created() {
        this.findDefaultIndex(window.location.href)

        router.on('start', (event) => {
            this.findDefaultIndex(event.detail.visit.url.href)
        })
    },
    data: () => ({
        activeIndex: null,
        menuItems: [
            {
                title: 'Пациенты',
                route: route('patients.index'),
                permissions: []
            },
            {
                title: 'Дерматопатология',
                route: route('patients.all'),
                permissions: ['read_all_patients']
            },
            {
                title: 'Выплаты',
                route: route('payments.index'),
                permissions: ['payments_manage']
            },
            {
                title: 'Управление',
                permissions: ['read_doctors', 'read_users', 'read_roles'],
                childs: [
                    {
                        title: 'Врачи',
                        route: route('doctors.index'),
                        permissions: ['read_doctors']
                    },
                    {
                        title: 'Пользователи',
                        route: route('users.index'),
                        permissions: ['read_users']
                    },
                    {
                        title: 'Мед. учреждения',
                        route: route('medical-clinics.index'),
                        permissions: ['medical-clinics.index']
                    },
                    {
                        title: 'Локации',
                        route: route('locations.index'),
                        permissions: ['read_locations']
                    },
                    {
                        title: 'Роли',
                        route: route('roles.index'),
                        permissions: ['read_roles']
                    },
                ]
            },
        ]
    }),
    computed: {
        parentItems() {
          return this.menuItems
              .filter(
                  e => e.childs === undefined && this.hasAnyPermission(e.permissions)
              )
        },
        parentWithChilds() {
            return this.menuItems
                .filter(
                    e => e.childs !== undefined && this.hasAnyPermission(e.permissions)
                )
        }
    },
    methods: {
        findDefaultIndex(url) {
            let childItems = [];

            for (let [i, item] of this.parentWithChilds.entries()) {
                for (let [chI, chItem] of item.childs.entries()) {
                    if(chItem.route === url) {
                        this.activeIndex =  `${i}-${chI}`
                        return;
                    }
                }
            }

            let index = [...this.parentItems.map(e => e.route), ...childItems].indexOf(url);

            this.activeIndex = index
        }
    }
}
</script>
