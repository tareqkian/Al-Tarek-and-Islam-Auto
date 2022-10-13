<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col">
        <button type="button" v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="typeDialog()">
          <i class="fe fe-plus"></i>
          Add Type
        </button>

        <div class="card">
          <div class="card-body">
            <DataTable :loading="autobanTypes.loading"
                       :value="autobanTypes.data"
                       :filters="filters"
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

              <Column field="type_title" header="Type Title">
                <template #body="value">
                  {{ t(value.data,'type_title') }}
                </template>
              </Column>
              <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                     @click="typeDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                     @click="typeDelete($event,val.data)"></i>
                </template>
              </Column>

              <template #footer v-if="autobanTypes.pagination">
                <Paginator :rows="+autobanTypes.pagination.per_page"
                           :totalRecords="autobanTypes.pagination.total"
                           :rowsPerPageOptions="[10,20,30]"
                           template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           current-page-report-template="Showing {first} to {last} of {totalRecords}"
                           @page="AutobanStore.initAutobanTypes($event)"></Paginator>
              </template>

            </DataTable>
          </div>
        </div>
      </div>
    </div>

    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="typeDialogShow">
      <form @submit.prevent="handleType">
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedType.en.type_title"
                 :class="[errors['en.type_title'] ? 'is-invalid' : '']"
                 placeholder="type Title">
          <label> type Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.type_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedType.ar.type_title"
                 :class="[errors['ar.type_title'] ? 'is-invalid' : '']"
                 placeholder="type Title">
          <label> type Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.type_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="typeDialogShow = !typeDialogShow">Close</button>
        </div>
      </form>
    </Dialog>

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Dialog from "primevue/dialog";
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, ref} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();

const expandedRowGroups = ref(null)
const AutobanStore = useAutobanStore()

const autobanTypes = computed(()=>AutobanStore.autobanTypes)
AutobanStore.initAutobanTypes()

const errors = ref([])
const loading = ref(false);

const typeDialogShow = ref(false)
const selectedType = ref({
  id: null,
  en: {type_title: null,},
  ar: {type_title: null,},
})
const typeDialog = (type = {})=>{
  errors.value = []
  typeDialogShow.value = !typeDialogShow.value
  selectedType.value.id = (type.id || null)
  selectedType.value.en.type_title = (t(type,'type_title','en') || null)
  selectedType.value.ar.type_title = (t(type,'type_title','ar') || null)
}
const handleType = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobanTypes(selectedType.value)
    typeDialogShow.value = !typeDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const typeDelete = (event,type)=>{
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
      try {
        await AutobanStore.distroyAutobanType(type)
        toast.add({
          closable: false,
          severity: "success",
          summary: "type",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "type",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

/*
Echo.channel("TypesEvent")
  .listen('TypeAdder',({type})=>{
    if ( AutobanStore.autobanTypes.data.length )
      AutobanStore.autobanTypes.data = [...AutobanStore.autobanTypes.data, type]
    else AutobanStore.autobanTypes.data = [type]
  })
  .listen('TypeEditor',({type})=>{
    if ( AutobanStore.autobanTypes.data.length ) {
      const typeIndex = AutobanStore.autobanTypes.data.findIndex(x => x.id === type.id);
      AutobanStore.autobanTypes.data = [
        ...AutobanStore.autobanTypes.data.slice(0,typeIndex),
        {...type},
        ...AutobanStore.autobanTypes.data.slice(typeIndex+1)
      ]
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.map(x=>{
        if ( x.type.id === type.id ) {
          x.type = {...type}
        }
        return x;
      })
    }
  })
  .listen('TypeDeleter',({type})=>{
    if ( AutobanStore.autobanTypes.data.length ) {
      const typeIndex = AutobanStore.autobanTypes.data.findIndex(x => x.id === type.id);
      AutobanStore.autobanTypes.data = [
        ...AutobanStore.autobanTypes.data.slice(0,typeIndex),
        ...AutobanStore.autobanTypes.data.slice(typeIndex+1)
      ]
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.filter(x=>x.type.id!==type.id)
    }
  })
*/

</script>
