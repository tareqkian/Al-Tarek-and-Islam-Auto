<template>
  <PageLayout :pageTitle="this.$route.meta.pageTitle">
    <button class="btn btn-primary mb-2" @click="roleDialog()">
      <i class="fe fe-plus"></i>
      Add New Role
    </button>
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="nav flex-column admisetting-tabs" role="tablist" aria-orientation="vertical">
            <Loading v-if="roles.loading" />
            <a v-else
               class="nav-link d-flex justify-content-between"
               data-bs-toggle="pill"
               v-for="(role, index) in roles.data" :key="index"
               @click="selectedRole(role)" :href="`#${roles.data[0].name}`" role="tab">
              <div>
                {{ t(role,"display_name") }}
<!--                {{ role.display_name }}-->
<!--                <small class="text-muted ms-3"> {{ t(role,"name") }} </small>-->
                <small class="text-muted ms-3"> {{ role.name }} </small>
              </div>
              <div>
                <i class="fa fa-edit text-info mx-1" @click="roleDialog(role)"></i>
                <i class="fa fa-trash text-danger mx-1" @click="roleDelete($event,role)"></i>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="col-xl-9">
        <div class="tab-content">
          <div class="tab-pane" :id="!roles.data.length || roles.data[0].name" role="tabpanel">
            <div class="card">
              <div class="card-body">
                <div class="row text-center">
                  <DataTable :loading="permissions.loading || rolePermissions.loading"
                             :value="permissions.data"
                             :filters="filters" :rows-per-page-options="[15,30,60]"
                             row-group-mode="rowspan" group-rows-by="table_name" paginator :rows="15"
                             paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                             current-page-report-template="Showing {first} to {last} of {totalRecords}"
                             table-class="table table-sm table-vcenter text-nowrap table-bordered border-bottom table-striped table-hover">
                    <template #loading>
                      <Loading />
                    </template>
                    <template #header>
                      <div class="search-element w-25 ms-auto mx-3 mb-4">
                        <input type="search" class="form-control header-search"
                               v-model="filters['global'].value" placeholder="Search…"
                               aria-label="Search" tabindex="1">
                        <i class="feather feather-search position-absolute"
                           style="top: 30%;right: 10px"></i>
                      </div>
                    </template>
                    <Column :sortable="true" class="text-capitalize" field="table_name" header="Table Name">
                      <template #body="value">
                        {{ value.data[value.field].replace(/_/g,' ') }}
                      </template>
                    </Column>
                    <Column :sortable="true" class="text-capitalize" header="browse" width="10%">
                      <template #body="value">
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox"
                                 @change="handleRolePermissions" :name='JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("browse_"))[0].id'
                                 v-model='rolePermissions.data.permissions[JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("browse_"))[0].id]'>
                        </div>
                      </template>
                    </Column>
                    <Column :sortable="true" class="text-capitalize" header="read" width="10%">
                      <template #body="value">
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox"
                                 @change="handleRolePermissions" :name='JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("read_"))[0].id'
                                 v-model='rolePermissions.data.permissions[JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("read_"))[0].id]'>
                        </div>
                      </template>
                    </Column>
                    <Column :sortable="true" class="text-capitalize" header="edit" width="10%">
                      <template #body="value">
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox"
                                 @change="handleRolePermissions" :name='JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("edit_"))[0].id'
                                 v-model='rolePermissions.data.permissions[JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("edit_"))[0].id]'>
                        </div>
                      </template>
                    </Column>
                    <Column :sortable="true" class="text-capitalize" header="add" width="10%">
                      <template #body="value">
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox"
                                 @change="handleRolePermissions" :name='JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("add_"))[0].id'
                                 v-model='rolePermissions.data.permissions[JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("add_"))[0].id]'>
                        </div>
                      </template>
                    </Column>
                    <Column :sortable="true" class="text-capitalize" header="delete" width="10%">
                      <template #body="value">
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox"
                                 @change="handleRolePermissions" :name='JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("delete_"))[0].id'
                                 v-model='rolePermissions.data.permissions[JSON.parse("["+permissions.data.filter(x=>x.table_name===value.data.table_name)[0].total+"]").filter(x=>x.key.includes("delete_"))[0].id]'>
                        </div>
                      </template>
                    </Column>
                  </DataTable>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- addRole -->
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="roleDialogShow">
      <form @submit.prevent="addNewRole">
        <template v-for="(input,index) in newRole" :key="index">
          <div v-if="index !== 'id'" class="form-floating my-2">
            <input class="form-control"
                   v-model="newRole[index]"
                   :class="[errors[index] ? 'is-invalid' : '']"
                   :name="index"
                   :placeholder="index">
            <label> {{ index }} </label>
            <div class="invalid-feedback">
              <ul>
                <li v-for="err in errors[index]" :key="err"> {{err}} </li>
              </ul>
            </div>
          </div>
        </template>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[newRoleLoading ? 'btn-loading' : '']">Save</button>
          <button type="button" class="btn btn-secondary" @click="roleDialogShow = !roleDialogShow">Close</button>
        </div>
      </form>
    </Dialog>

<!--    <ConfirmPopup>
      <template #message="data">
        <div class="popover-arrow" style="position: absolute; left: 0px; transform: translate(129px, 0px);"></div>
        <h3 class="popover-header">{{ data.message.message.header }}</h3>
        <div class="popover-body d-flex align-items-center">
          <i class="fs-5 me-3 text-danger" :class="[data.message.icon]"></i>
          {{ data.message.message.body }}
        </div>
      </template>
    </ConfirmPopup>-->
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue"
import Loading from "/src/components/Loading.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from "primevue/dialog";

import { useToast } from "primevue/usetoast"
import { useConfirm } from "primevue/useconfirm"
import { FilterMatchMode } from 'primevue/api';
import { useRolesStore } from "../../store/Roles";
import { usePermissions } from "../../store/Permissions";
import {computed, inject, ref} from "vue";

const t = inject("t")

const rolesStore = useRolesStore();
const permissionsStore = usePermissions();
const toast = useToast()
const confirm = useConfirm()
const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const roleDialogShow = ref(false);

const roles = computed(()=>rolesStore.roles)
// roles.value.data.length || rolesStore.initROLES()
rolesStore.initROLES()

const rolePermissions = computed(()=>rolesStore.rolePermissions)
const permissions = computed(()=>permissionsStore.permissions)

const errors = ref({})
const newRoleLoading = ref(false);
const newRole = {
  name: null,
  display_name: null,
}


const roleDialog = (role = {})=>{
  roleDialogShow.value = !roleDialogShow.value
  newRole.id = role.id || null;
  newRole.name = role.name || null;
  /*newRole.display_name = role.display_name || null;*/
  newRole.display_name = t(role,"display_name") || null;
  errors.value = {}
}

const addNewRole = async ()=>{
  try {
    newRoleLoading.value = true;
    errors.value = {}
    await rolesStore.handleRole(newRole)
    roleDialogShow.value = !roleDialogShow.value
    newRoleLoading.value = false;
  } catch (e) {
    errors.value = e;
    newRoleLoading.value = false;
  }
}
const selectedRole = (role)=>{
  rolesStore.initROLE(role)
  // permissions.value.data.length || permissionsStore.initPERMISSIONS(role)
  permissionsStore.initPERMISSIONS(role)
}
const handleRolePermissions = async () => {
  try {
    let payload = rolePermissions.value.data;
    payload.rolePermissions = Object.keys(payload.permissions)
      .filter(x=>payload.permissions[x])
      .map((key, index)=>[key] = key);
    await rolesStore.updatePERMISSIONS(payload)
    toast.add({
      closable: false,
      severity: 'success',
      summary: 'Permissions',
      detail: 'Edited Successfully',
      life: 3000
    })
  } catch (e) {
    toast.add({
      closable: false,
      severity: 'danger',
      summary: 'Permissions',
      detail: 'Faild',
      life: 3000
    })
  }
}
const roleDelete = (event,role)=>{
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
      await rolesStore.distroyRole(role)
      toast.add({
        closable: false,
        severity: "success",
        summary: "Role",
        detail: "has been Deleted",
        life: 3000
      })
    },
    reject:()=>{/**/}
  });
}

Echo.channel("RolesEvent")
  .listen("RoleAdder",(data)=>{
    if ( rolesStore.roles.data.length ) {
      rolesStore.roles.data = [
        ...rolesStore.roles.data,
        data.role
      ]
    }
  })
  .listen("RoleEditor",(data)=>{
    if ( rolesStore.roles.data.length ) {
      const roleIndex = rolesStore.roles.data.findIndex(x => x.id === data.role.id);
      rolesStore.roles.data = [
        ...rolesStore.roles.data.slice(0,roleIndex),
        {...data.role},
        ...rolesStore.roles.data.slice(roleIndex+1)
      ]
    }
  })
  .listen("RoleDeletor",(data)=>{
    if ( rolesStore.roles.data.length ) {
      const roleIndex = rolesStore.roles.data.findIndex(x => x.id === data.role.id);
      rolesStore.roles.data = [
        ...rolesStore.roles.data.slice(0,roleIndex),
        ...rolesStore.roles.data.slice(roleIndex+1)
      ]
    }
  })

</script>

<style lang="scss" scoped>
::v-deep(.p-paginator) {
  .p-paginator-current {
    margin-left: auto;
  }
}
</style>
