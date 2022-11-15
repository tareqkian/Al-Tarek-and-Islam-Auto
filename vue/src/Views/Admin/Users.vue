<template>
  <PageLayoutVue>
    <button v-if="$can(`add_${route.meta.permissionsLayout}`)" class="btn btn-primary mb-2" @click="userDialog()">
      <i class="fe fe-plus"></i>
      Add New User
    </button>
    <div class="card">
      <div class="card-body">
        <DataTable :loading="users.loading" :value="users.data"
                   :filters="filters" :rows-per-page-options="[5,15,30]"
                   paginator :rows="5"
                   paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   current-page-report-template="Showing {first} to {last} of {totalRecords}"
                   table-class="table table-vcenter text-nowrap border-bottom table-striped table-hover">
          <template #loading>
            <Loading />
          </template>
          <template #header>
            <div class="row flex-row-reverse justify-content-center justify-content-md-start w-100 m-0">
              <div class="search-element col-10 col-md-3 mx-3 mb-4 p-0">
                <input type="search" class="form-control header-search"
                       v-model="filters['global'].value" placeholder="Search…"
                       aria-label="Search" tabindex="1">
                <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
              </div>
            </div>
          </template>
          <Column :sortable="true" field="name" header="Name"></Column>
          <Column :sortable="true" field="email" header="Email"></Column>
          <Column field="avatar" header="Avatar">
            <template #body="val">
                <Image imageStyle="width: 60px" :src="val.data[val.field] !== BASE_URL ? val.data[val.field] : `${BASE_URL}/users/default.png`" preview/>
            </template>
          </Column>
          <Column :sortable="true" field="role.display_name" header="Role">
            <template #body="val">
              {{ t(val.data.role,"display_name") }}
            </template>
          </Column>
          <Column :sortable="true" field="created_at" header="Created at"></Column>
          <Column :sortable="true" field="updated_at" header="Updated at"></Column>
          <Column v-if="$can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`)" header="Actions">
            <template #body="val">
              <i class="fa fa-edit text-info mx-1" v-if="$can(`edit_${route.meta.permissionsLayout}`)" @click="userDialog(val.data)"></i>
              <i class="fa fa-trash text-danger mx-1" v-if="$can(`delete_${route.meta.permissionsLayout}`)" @click="userDelete($event,val.data)"></i>
            </template>
          </Column>
        </DataTable>
      </div>
    </div>

    <handleUser
      v-model="userDialogShow"
      :loading="loading"
      :errors="errors"
      :defaultImg="defaultImg"
      :userForm="userForm"
      @handleUserForm="handleUserForm"
    />
  </PageLayoutVue>
</template>

<script setup>
import PageLayoutVue from "/src/components/Layouts/PageLayout.vue"
import Loading from "/src/components/Loading.vue";
import handleUser from "/src/components/User/handleUser.vue"
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Avatar from 'primevue/avatar';
import Image from 'primevue/image';

import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import { computed, ref } from "vue";
import { FilterMatchMode } from "primevue/api";
import { useUsersStore } from "/src/store/Users";
import { useRoute } from "vue-router";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const confirm = useConfirm();
const toast = useToast();
const route = useRoute();

const usersStore = useUsersStore();
const users = computed(()=>usersStore.users)
// users.value.data.length || usersStore.initUsers();
usersStore.initUsers();

const userDialogShow = ref(false);
const loading = ref(false);
const errors = ref({});
const defaultImg = ref(null)
const userForm = ref({
  name: null,
  email: null,
  avatar: null,
  role_id: null,
  Desktop: 0,
  Mobile: 0
})
const userDialog = (user = {})=>{
  userDialogShow.value = !userDialogShow.value
  defaultImg.value = (user.avatar !== BASE_URL ? user.avatar : `${BASE_URL}/users/default.png`)
  userForm.value.id = user.id || null
  userForm.value.name = user.name || null
  userForm.value.email = user.email || null
  userForm.value.Desktop = (user.settings ? JSON.parse(user.settings).devices[0].count : null)
  userForm.value.Mobile = (user.settings ? JSON.parse(user.settings).devices[1].count : null)
  userForm.value.avatar = null
  userForm.value.role_id = (user.role ? user.role.id : null)
}
const handleUserForm = async ()=>{
  try {
    loading.value = true
    errors.value = {}
    if ( userForm.value.id ) await usersStore.updateUser(userForm.value)
    else await usersStore.storeUser(userForm.value)
    userDialogShow.value = !userDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "Users",
      detail: "Has Been Updated",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const userDelete = (event,user)=>{
  confirm.require({
    target: event.currentTarget,
    message: {
      header: 'Are Ya Sure',
      body: "You won't be able to revert this!"
    },
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: "Yes, delete it!",
    acceptClass: 'btn btn-sm btn-danger mx-1',
    rejectLabel: "Cancel",
    rejectClass: 'btn btn-sm btn-secondary mx-1',
    accept: async()=>{
      await usersStore.distroyUser(user)
      toast.add({
        closable: false,
        severity: "success",
        summary: "User",
        detail: "has been Deleted",
        life: 3000
      })
    }
  });
}
</script>

