<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col">
        <button v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="brandDialog()">
          <i class="fe fe-plus"></i>
          Add Brand
        </button>
        <button v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="modelDialog()">
          <i class="fe fe-plus"></i>
          Add Model
        </button>

        <div class="card">
          <div class="card-header border-bottom-0">
            <h4 class="card-title"> Brands - Models </h4>
          </div>
          <div class="card-body">
            <DataTable :loading="autobanModels.loading"
                       :value="autobanModels.data"
                       :filters="filters"
                       :rows-per-page-options="[5,15,30]"
                       rowGroupMode="rowspan"
                       groupRowsBy="brand.brand_title"
                       sortMode="single"
                       sortField="brand.brand_title"
                       :sortOrder="1"
                       paginator
                       :rows="5"
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

              <Column :sortable="true" field="brand.brand_title" header="brand title">
                <template #body="value">
                  <Image imageStyle="width: 50px" :src="value.data.brand.brand_image" preview/>
                  <span class="mx-2">{{ t(value.data.brand,'brand_title') }}</span>

                  <div class="d-inline-block float-end">
                    <i class="fa fa-edit text-info mx-1"
                       v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                       @click="brandDialog(value.data.brand)"></i>
                    <i class="fa fa-trash text-danger mx-1"
                       v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                       @click="brandDelete($event,value.data.brand)"></i>
                  </div>
                </template>
              </Column>


              <Column field="model_title" header="Model Title">
                <template #body="value">
                  {{ t(value.data,'model_title') }}
                </template>
              </Column>
              <Column field="model_image" header="model Image">
                <template #body="val">
                  <Image width="80" :src="val.data.model_image" preview/>
                </template>
              </Column>

              <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                     @click="modelDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                     @click="modelDelete($event,val.data)"></i>
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
                 v-model="selectedBrand.en.brand_title"
                 :class="[errors['en.brand_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Brand Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.brand_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedBrand.ar.brand_title"
                 :class="[errors['ar.brand_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Brand Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.brand_title']" :key="err"> {{err}} </li>
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

    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="modelDialogShow">
      <form @submit.prevent="handleModel">
        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedModels.autoban_brand_id"
            :options="autobanBrands.data"
            filter
            filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'brand_title')"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_brand_id ? 'is-invalid' : '']"
            placeholder="Brand" />
          <label> Brand </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_brand_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <a v-if="!selectedModels.id" tabindex="-1" class="btn btn-success btn-sm" @click.prevent="newModel()">
          Add Model
        </a>
        <Accordion :activeIndex="selectedModels.data.length-1">
          <AccordionTab v-for="(models,index) in selectedModels.data" :key="index">
            <template #header >
              <span :class="[(!errors[`data.${index}.model_image`] && !errors[`data.${index}.ar.model_title`] && !errors[`data.${index}.en.model_title`]) || 'text-danger']" class="mx-3">
                {{ `${models.en.model_title || ''} - ${models.ar.model_title || ''}` }}
              </span>
              <a v-if="index > 0" class="btn btn-danger btn-sm ms-auto" @click.stop="removeModel(index)">
                Remove Model
              </a>
            </template>
            <div class="form-floating my-2">
              <input class="form-control"
                     v-model="models.en.model_title"
                     :class="[errors[`data.${index}.en.model_title`] ? 'is-invalid' : '']"
                     placeholder="Brand Title">
              <label> Model Title EN </label>
              <div class="invalid-feedback">
                <ul>
                  <li v-for="err in errors[`data.${index}.en.model_title`]" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
            <div class="form-floating my-2">
              <input class="form-control"
                     v-model="models.ar.model_title"
                     :class="[errors[`data.${index}.ar.model_title`] ? 'is-invalid' : '']"
                     placeholder="Brand Title">
              <label> Model Title AR </label>
              <div class="invalid-feedback">
                <ul>
                  <li v-for="err in errors[`data.${index}.ar.model_title`]" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
            <div class="my-2">
              <MyFileUpload :class="[!errors[`data.${index}.model_image`] || 'text-danger']" v-model="models.model_image" :defaultImg="models.model_image || 'asd'" />
              <div v-if="errors[`data.${index}.model_image`]" class="text-danger mt-2">
                <ul>
                  <li v-for="err in errors[`data.${index}.model_image`]" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
          </AccordionTab>
<!--
          <AccordionTab header="Header I">
            <div class="form-floating my-2">
              <input class="form-control"
                     v-model="selectedModel.en.model_title"
                     :class="[errors['en.model_title'] ? 'is-invalid' : '']"
                     placeholder="Brand Title">
              <label> Model Title EN </label>
              <div class="invalid-feedback">
                <ul>
                  <li v-for="err in errors['en.model_title']" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
            <div class="form-floating my-2">
              <input class="form-control"
                     v-model="selectedModel.ar.model_title"
                     :class="[errors['ar.model_title'] ? 'is-invalid' : '']"
                     placeholder="Brand Title">
              <label> Model Title AR </label>
              <div class="invalid-feedback">
                <ul>
                  <li v-for="err in errors['ar.model_title']" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
            <div class="my-2">
              <MyFileUpload :class="[!errors.model_image || 'text-danger']" v-model="selectedModel.model_image" :defaultImg="selectedModel.model_image || 'asd'" />
              <div v-if="errors.model_image" class="text-danger mt-2">
                <ul>
                  <li v-for="err in errors.model_image" :key="err"> {{err}} </li>
                </ul>
              </div>
            </div>
          </AccordionTab>
-->
        </Accordion>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="modelDialogShow = !modelDialogShow">Close</button>
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
import Dropdown from "primevue/dropdown"
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';

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
const autobanBrands = computed(()=>AutobanStore.autobanBrands)
AutobanStore.initAutobanBrands()

const autobanModels = computed(()=>AutobanStore.autobanModels)
AutobanStore.initAutobanModels()

const errors = ref([])
const loading = ref(false);

const brandDialogShow = ref(false)
const selectedBrand = ref({
  id: null,
  en: {
    brand_title: null,
  },
  ar: {
    brand_title: null,
  },
  brand_image: null
})
const brandDialog = (brand = {})=>{
  errors.value = []
  brandDialogShow.value = !brandDialogShow.value
  selectedBrand.value.id = (brand.id || null)
  selectedBrand.value.en.brand_title = (t(brand,'brand_title','en') || null)
  selectedBrand.value.ar.brand_title = (t(brand,'brand_title','ar') || null)
  selectedBrand.value.brand_image = (brand.brand_image || null)
}
const handleBrand = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobanBrands(selectedBrand.value)
    brandDialogShow.value = !brandDialogShow.value
    if ( !selectedBrand.value.id ) {
      modelDialogShow.value = !modelDialogShow.value
    }
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
          summary: "Brand",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Brand",
          detail: e,
          life: 3000
        })
      }
    }
  });
}


const modelDialogShow = ref(false)
const selectedModels = ref({
  autoban_brand_id: null,
  data: [
    {
      autoban_brand_id: null,
      en: {model_title: null},
      ar: {model_title: null},
      model_image: null
    }
  ]
})
const modelDialog = (model = {})=>{
  errors.value = []
  modelDialogShow.value = !modelDialogShow.value
  selectedModels.value = {
    id: (model.id || null),
    autoban_brand_id: (model.brand ? model.brand.id : null),
    data: [
      {
        en: { model_title: (t(model, 'model_title', 'en') || null) },
        ar: { model_title: (t(model, 'model_title', 'ar') || null) },
        model_image: (model.model_image || null),
      }
    ]
  }
}
const newModel = ()=>{
  selectedModels.value.data.push({
    en: { model_title: null },
    ar: { model_title: null },
    model_image: null
  })
}
const removeModel = (index)=> {
  selectedModels.value.data = [
    ...selectedModels.value.data.slice(0,index),
    ...selectedModels.value.data.slice(index+1)
  ]
}
const handleModel = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobanModels(selectedModels.value)
    modelDialogShow.value = !modelDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const modelDelete = (event,model)=>{
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
        await AutobanStore.distroyAutobanModel(model)
        toast.add({
          closable: false,
          severity: "success",
          summary: "Model",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Model",
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
  .listen('ModelAdder',({model})=>{
    if ( AutobanStore.autobanModels.data.length ) AutobanStore.autobanModels.data = [...AutobanStore.autobanModels.data, ...model]
    else AutobanStore.autobanModels.data = [...model]
  })
  .listen('BrandEditor',({brand})=>{
    if ( AutobanStore.autobanBrands.data.length ) {
      const brandIndex = AutobanStore.autobanBrands.data.findIndex(x => x.id === brand.id);
      AutobanStore.autobanBrands.data = [
        ...AutobanStore.autobanBrands.data.slice(0,brandIndex),
        {...brand},
        ...AutobanStore.autobanBrands.data.slice(brandIndex+1)
      ]
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.map(x=>{
        if ( x.brand.id === brand.id ) {
          x.brand = {...brand}
        }
        return x;
      })
    }
  })
  .listen('ModelEditor',({model})=>{
    if ( AutobanStore.autobanModels.data.length ) {
      const modelIndex = AutobanStore.autobanModels.data.findIndex(x => x.id === model.id);
      AutobanStore.autobanModels.data = [
        ...AutobanStore.autobanModels.data.slice(0,modelIndex),
        {...model},
        ...AutobanStore.autobanModels.data.slice(modelIndex+1)
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
      AutobanStore.autobanModels.data = AutobanStore.autobanModels.data.filter(x=>x.brand.id!==brand.id)
    }
  })
  .listen('ModelDeleter',({model})=>{
    if ( AutobanStore.autobanModels.data.length ) {
      const modelIndex = AutobanStore.autobanModels.data.findIndex(x => x.id === model.id);
      AutobanStore.autobanModels.data = [
        ...AutobanStore.autobanModels.data.slice(0,modelIndex),
        ...AutobanStore.autobanModels.data.slice(modelIndex+1)
      ]
    }
  })
</script>
