<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col-lg-12 col-xl-6 col-md-12 col-sm-12">
        <button v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2" @click="brandDialog()">
          <i class="fe fe-plus"></i>
          Add New Brand
        </button>

        <div class="card">
          <div class="card-header border-bottom-0">
            <h4 class="card-title"> Brands </h4>
          </div>
          <div class="card-body">
            <DataTable :loading="autobanBrands.loading" :value="autobanBrands.data"
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
                  <div class="search-element col-10 col-md-6 mx-3 mb-4 p-0">
                    <input type="search" class="form-control header-search"
                           v-model="filters['global'].value" placeholder="Search…"
                           aria-label="Search" tabindex="1">
                    <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
                  </div>
                </div>
              </template>
              <Column :sortable="true" field="brand_title" header="brand title">
                <template #body="value">
                  {{ t(value.data,'brand_title') }}
                </template>
              </Column>
              <Column field="brand_image" header="brand Image">
                <template #body="val">
                  <Image imageStyle="width: 80px" :src="val.data[val.field]" preview/>
                </template>
              </Column>
              <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                     @click="brandDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                     @click="brandDelete($event,val.data)"></i>
                </template>
              </Column>

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
      v-model:visible="brandDialogShow">
      <form @submit.prevent="handleBrand">
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedBrand.brand_title"
                 :class="[errors.brand_title ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Brand Title </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.brand_title" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="my-2">
          <MyFileUpload :class="[!errors.brand_image || 'text-danger']" v-model="selectedBrand.brand_image" :defaultImg="selectedBrand.brand_image || 'asd'" />
          <div v-if="errors.brand_image" class="text-danger mt-2">
            <ul>
              <li v-for="err in errors.brand_image" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="brandDialogShow = !brandDialogShow">Close</button>
        </div>
      </form>
    </Dialog>


  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Dialog from "primevue/dialog";
import MyFileUpload from "/src/components/MyFileUpload.vue"
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Image from "primevue/image";
import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, ref} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();


const AutobanStore = useAutobanStore()
const autobanBrands = computed(()=>AutobanStore.autobanBrands)
AutobanStore.initAutobanBrands()

const brandDialogShow = ref(false)
const selectedBrand = ref({
  id: null,
  brand_title: null,
  brand_image: null
})
const errors = ref([])
const loading = ref(false);
const brandDialog = (brand = {})=>{
  errors.value = []
  brandDialogShow.value = !brandDialogShow.value
  selectedBrand.value.id = (brand.id || null)
  selectedBrand.value.brand_title = (t(brand,'brand_title') || null)
  selectedBrand.value.brand_image = (brand.brand_image || null)
}
const handleBrand = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobanBrands(selectedBrand.value)
    brandDialogShow.value = !brandDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}



const brandDelete = (event,brand)=>{
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
        await AutobanStore.distroyAutobanBrand(brand)
        toast.add({
          closable: false,
          severity: "success",
          summary: "User",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "User",
          detail: e,
          life: 3000
        })
      }
    }
  });
}



Echo.channel("BrandsEvent")
  .listen('BrandAdder',({brand})=>{
    if ( AutobanStore.autobanBrands.data.length ) AutobanStore.autobanBrands.data = [...AutobanStore.autobanBrands.data, brand]
    else AutobanStore.autobanBrands.data = [brand]
  })
  .listen('BrandEditor',({brand})=>{
    if ( AutobanStore.autobanBrands.data.length ) {
      const brandIndex = AutobanStore.autobanBrands.data.findIndex(x => x.id === brand.id);
      AutobanStore.autobanBrands.data = [
        ...AutobanStore.autobanBrands.data.slice(0,brandIndex),
        {...brand},
        ...AutobanStore.autobanBrands.data.slice(brandIndex+1)
      ]
    }
  })
  .listen('BrandDeleter',({brand})=>{
    if ( AutobanStore.autobanBrands.data.length ) {
      const brandIndex = AutobanStore.autobanBrands.data.findIndex(x => x.id === brand.id);
      AutobanStore.autobanBrands.data = [
        ...AutobanStore.autobanBrands.data.slice(0,brandIndex),
        ...AutobanStore.autobanBrands.data.slice(brandIndex+1)
      ]
    }
  })

</script>
