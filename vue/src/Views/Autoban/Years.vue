<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col">
        <button v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="yearDialog()">
          <i class="fe fe-plus"></i>
          Add Year
        </button>

        <div class="card">
          <div class="card-body">
            <DataTable :loading="autobanYears.loading"
                       :value="autobanYears.data"
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

              <Column field="year_number" header="Year Number">
                <template #body="value">
                  {{ t(value.data,'year_number') }}
                </template>
              </Column>
              <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                     @click="yearDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                     @click="yearDelete($event,val.data)"></i>
                </template>
              </Column>

              <template #footer v-if="autobanYears.pagination">
                <Paginator :rows="+autobanYears.pagination.per_page"
                           :totalRecords="autobanYears.pagination.total"
                           :rowsPerPageOptions="[10,20,30]"
                           template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           current-page-report-template="Showing {first} to {last} of {totalRecords}"
                           @page="AutobanStore.initAutobanYears($event)"></Paginator>
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
      v-model:visible="yearDialogShow">
      <form @submit.prevent="handleYear">
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedYear.en.year_number"
                 :class="[errors['en.year_number'] ? 'is-invalid' : '']"
                 placeholder="year Number">
          <label> year Number </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.year_number']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="yearDialog()">Close</button>
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

const autobanYears = computed(()=>AutobanStore.autobanYears)
AutobanStore.initAutobanYears()

const errors = ref([])
const loading = ref(false);

const yearDialogShow = ref(false)
const selectedYear = ref({
  id: null,
  en: {year_number: null,},
})
const yearDialog = (year = {})=>{
  errors.value = []
  yearDialogShow.value = !yearDialogShow.value
  selectedYear.value.id = (year.id || null)
  selectedYear.value.en.year_number = (t(year,'year_number','en') || null)
}
const handleYear = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobanYears(selectedYear.value)
    yearDialogShow.value = !yearDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const yearDelete = (event,year)=>{
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
        await AutobanStore.distroyAutobanYear(year)
        toast.add({
          closable: false,
          severity: "success",
          summary: "year",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "year",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

Echo.channel("YearsEvent")
  .listen('YearAdder',({year})=>{
    if ( AutobanStore.autobanYears.data.length ) AutobanStore.autobanYears.data = [...AutobanStore.autobanYears.data, year]
    else AutobanStore.autobanYears.data = [year]
  })
  .listen('YearEditor',({year})=>{
    if ( AutobanStore.autobanYears.data.length ) {
      const yearIndex = AutobanStore.autobanYears.data.findIndex(x => x.id === year.id);
      AutobanStore.autobanYears.data = [
        ...AutobanStore.autobanYears.data.slice(0,yearIndex),
        {...year},
        ...AutobanStore.autobanYears.data.slice(yearIndex+1)
      ]
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.map(x=>{
        if ( x.year.id === year.id ) {
          x.year = {...year}
        }
        return x;
      })
    }
  })
  .listen('YearDeleter',({year})=>{
    if ( AutobanStore.autobanYears.data.length ) {
      const yearIndex = AutobanStore.autobanYears.data.findIndex(x => x.id === year.id);
      AutobanStore.autobanYears.data = [
        ...AutobanStore.autobanYears.data.slice(0,yearIndex),
        ...AutobanStore.autobanYears.data.slice(yearIndex+1)
      ]
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.filter(x=>x.year.id!==year.id)
    }
  })
</script>
