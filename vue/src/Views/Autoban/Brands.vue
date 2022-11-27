<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <button v-if="$can(`add_${route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="brandDialog()">
          <i class="fe fe-plus"></i>
          Add Brand
        </button>
        <button v-if="$can(`add_autoban_models`)" class="btn btn-primary mb-2 me-3" @click="modelDialog()">
          <i class="fe fe-plus"></i>
          Add Model
        </button>
        <div class="card">
          <div class="card-body">
            <DataTable :loading="autobanModels.loading"
                       :value="autobanModels.data"
                       :filters="filters"

                       filterDisplay="row"

                       rowGroupMode="rowspan"
                       groupRowsBy="brand.brand_title"
                       sortMode="single"

                       table-class="table table-vcenter text-nowrap border-bottom table-striped table-hover">
              <template #loading>
                <Loading />
              </template>
              <template #header>
                <div class="row flex-row-reverse justify-content-center justify-content-md-start w-100 m-0">
                  <div class="search-element col-10 col-md-3 mx-3 mb-4 p-0">
                    <input type="search" class="form-control header-search"
                           v-model="filter" placeholder="Search…"
                           aria-label="Search" tabindex="1">
                    <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
                  </div>
                </div>
              </template>

              <Column field="brand.brand_title" header="brand title" :showFilterMenu="false">
                <template #body="value">
                  <Image imageStyle="width: 50px" :src="value.data.brand.brand_image" preview/>
                  <span class="mx-2">{{ t(value.data.brand,'brand_title') }}</span>
                  <div class="d-inline-block float-end">
                    <i class="fa fa-edit text-info mx-1"
                       v-if="$can(`edit_autoban_models`)"
                       @click="brandDialog(value.data.brand)"></i>
                    <i class="fa fa-trash text-danger mx-1"
                       v-if="$can(`delete_autoban_models`)"
                       @click="brandDelete($event,value.data.brand)"></i>
                  </div>
                </template>
                <template #filter="{filterModel}">
                  <Dropdown
                    show-clear :loading="autobanBrandsAll.loading"
                    v-model="filtersCol.brand_title.value" placeholder="Brand"
                    @change="clear('brand_title')"
                    :options="autobanBrandsAll.data"
                    :option-label="opt=>t(opt,'brand_title')"
                    filter filter-placeholder="Search"
                    class="form-control d-flex align-items-stretch" />
                </template>
              </Column>
              <Column field="model_title" header="Model Title" :showFilterMenu="false">
                <template #body="value">
                  {{ t(value.data,'model_title') }}
                </template>

                <template #filter="{filterModel}">
                  <Dropdown
                    show-clear :loading="autobanModelsAll.loading"
                    v-model="filtersCol.model_title.value" placeholder="Model"
                    @change="clear('model_title')"
                    :options="autobanModelsAll.data"
                    :option-label="opt=>t(opt,'model_title')"
                    filter filter-placeholder="Search"
                    class="form-control d-flex align-items-stretch" />
                </template>

              </Column>
              <Column field="model_image" header="model Image">
                <template #body="val">
                  <Image width="80" :src="val.data.model_image" preview/>
                </template>
              </Column>
              <Column header="Gallery">
                <template #body="val">
                  <i class="ti ti-gallery text-info fs-18" @click="galleryDialog(val.data)"></i>
                </template>
              </Column>
              <Column v-if="$can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                     @click="modelDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                     @click="modelDelete($event,val.data)"></i>
                </template>
              </Column>

              <template #footer v-if="autobanModels.pagination">
                <Paginator :rows="+autobanModels.pagination.per_page"
                           :totalRecords="autobanModels.pagination.total"
                           :rowsPerPageOptions="[10,20,30]"
                           template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           current-page-report-template="Showing {first} to {last} of {totalRecords}"
                           @page="AutobanStore.initAutobanModels($event,filter)"></Paginator>
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>

    <Dialog
      modal class="modal-content modal-lg"
      content-class="modal-body"
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
      modal class="modal-content modal-lg"
      content-class="modal-body"
      v-model:visible="modelDialogShow">
      <form @submit.prevent="handleModel">

        <BrandDropDown v-model="selectedModels.autoban_brand_id" :error="errors.autoban_brand_id" />

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

        </Accordion>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="modelDialogShow = !modelDialogShow">Close</button>
        </div>
      </form>
    </Dialog>
    <Dialog
      modal class="modal-content modal-lg"
      content-class="modal-body"
      v-model:visible="galleryDialogShow">
      <!--:max-file-size="1000000"-->
      <FileUpload
        :multiple="true"
        accept="image/*"
        @before-upload="uploadNewCarGallery($event)"
        @upload="true"
        @clear="clearGallery"
        @select="onSelectedFiles">
        <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
          <div class="btn-group">
            <button
              @click="chooseCallback()"
              class="btn btn-sm btn-primary d-flex align-items-center">
              <i class="bx bx-plus me-1"></i> Choose
            </button>
            <button
              @click="uploadCallback()"
              :disabled="!files || files.length === 0"
              class="btn btn-sm btn-success d-flex align-items-center"
              :class="!loading || `btn-loading`">
              <i class="bx bx-upload me-1"></i> Upload
            </button>
            <button
              @click="clearCallback()"
              :disabled="!files || files.length === 0"
              class="btn btn-sm btn-danger d-flex align-items-center">
              <i class="pi pi-times me-1"></i> Cancel
            </button>
          </div>
        </template>
        <template #content="{ files, uploadedFiles, onUploadedFileRemove, onFileRemove, progress, messages }">
          <div v-for="(msg,index) in messages" class="alert alert-danger my-3" role="alert">
            <button @click="messages.splice(index, 1)" class="btn-close" aria-hidden="true">×</button>
            <i class="fa fa-frown-o me-2" aria-hidden="true"></i>
            {{ msg }}
          </div>
          <div v-if="files.length > 0">
            <h5 class="mt-5 mb-0"> New Images: </h5>
            <div class="row">
              <div v-for="(file, index) of files" :key="file.name + file.type + file.size"
                   class="col-2 text-center card m-3 py-3 d-flex justify-content-between">
                <div class="d-flex align-items-center justify-content-center">
                  <Image width="80" :src="file.objectURL" preview/>
                </div>
                <div class="pt-4">
                  <p class="font-semibold border-bottom text-truncate mb-2">{{ file.name }}</p>
                  <div>{{ t(file,'size',0,1,0) }} KB</div>
                  <i @click="clearImage(index)" class="fa fa-times-circle text-danger position-absolute" style="top: 0; right: 0;"></i>
                </div>
              </div>
            </div>
          </div>

          <div v-if="uploadedFiles.length > 0">
<!--
            <h5 class="mt-5 mb-0"> Completed: </h5>
            <div class="row">
              <div v-for="(file, index) of uploadedFiles" :key="file.name + file.type + file.size"
                   class="col-2 text-center card m-3 py-3 d-flex justify-content-between">
                <div class="d-flex align-items-center justify-content-center">
                  <Image width="80" :src="file.objectURL" preview/>
                </div>
                <div class="pt-4">
                  <p class="font-semibold border-bottom text-truncate mb-2">{{ file.name }}</p>
                  <div>{{ t(file,'size',0,1,0) }} KB</div>
                  <i @click="clearImage(index)" class="fa fa-times-circle text-danger position-absolute" style="top: 0; right: 0;"></i>
                </div>
              </div>
            </div>
-->
          </div>

          <div v-if="ModelGallery.model.gallery.length > 0">
            <h4 class="mt-5 mb-0"> Images: </h4>
            <div class="row">
              <div v-for="(img, index) of ModelGallery.model.gallery"
                   :key="img.id"
                   class="col-2 text-center card m-3 py-3 d-flex justify-content-center">
                <div class="d-flex align-items-center justify-content-center">
                  <Image width="80" :src="img.image" preview/>
                  <i @click="deleteImage($event,img)" class="fa fa-trash text-danger position-absolute" style="top: 0; right: 0;"></i>
                </div>
              </div>
            </div>
          </div>

        </template>
        <template #empty>
          <div v-if="!ModelGallery.model.gallery.length" class="text-center">
            <i class="fe fe-upload-cloud fs-50"></i>
            <p class="m-0"><strong>Drag and Drop File</strong></p>
            <p class="m-0"><small class="text-muted">Your File Will be Added Automaticlly</small></p>
          </div>
        </template>
      </FileUpload>
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
import Paginator from "primevue/paginator";
import Image from "primevue/image";
import Dropdown from "primevue/dropdown"
import MultiSelect from "primevue/multiselect";
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import BrandDropDown from "../../components/Autoban/BrandDropDown.vue";
import FileUpload from 'primevue/fileupload';
import Galleria from 'primevue/galleria';

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, reactive, ref, watch} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";
import {useRoute} from "vue-router";
import _debounce from "lodash/debounce";


const filters = ref({
  'brand_title': {value: null},
  'model_title': {value: null},
})
const filtersCol = ref({
  'brand_title': {value: null},
  'model_title': {value: null},
})
const filter = ref("")

const t = inject("t");
const toast = useToast();
const confirm = useConfirm();
const route = useRoute();

const AutobanStore = useAutobanStore()

watch(
  ()=>filter.value,
  _debounce((val)=>{
    filtersCol.value = {
      'brand_title': {value: ''},
      'model_title': {value: ''},
    }
    AutobanStore.initAutobanModels({},val)
  },400)
)

const clear = (e) => {
  if ( e === 'brand_title' ) {
    filtersCol.value.model_title.value = null
    AutobanStore.initAutobanModels(
      {},
      (filtersCol.value[e].value ? filtersCol.value[e].value[e] : ''),
      'same',
      e
    )
    if ( filtersCol.value[e].value ) AutobanStore.initAutobanModel(filtersCol.value[e].value.id)
    else AutobanStore.initAutobanModels()
  }
  else {
    AutobanStore.initAutobanModels(
      {},
      filtersCol.value.brand_title.value
        ? filtersCol.value.brand_title.value.brand_title+' '+(filtersCol.value[e].value ? filtersCol.value[e].value[e] : '')
        : '',
      'like',
      e
    )
  }
}

const autobanModels = computed(()=>AutobanStore.autobanModels)
AutobanStore.initAutobanModels()

const autobanBrandsAll = computed(()=>AutobanStore.autobanBrands)
AutobanStore.initAutobanBrands()

const autobanModelsAll = computed(()=>AutobanStore.autobanModel)
AutobanStore.initAutobanModelsAll()

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
    const res = await AutobanStore.handleAutobanBrands(selectedBrand.value)
    brandDialogShow.value = !brandDialogShow.value
    if ( !selectedBrand.value.id ) {
      selectedModels.value.autoban_brand_id = res.id
      modelDialogShow.value = !modelDialogShow.value
    }
    toast.add({
      closable: false,
      severity: "success",
      summary: "brand",
      detail: "Success",
      life: 3000
    })
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
    toast.add({
      closable: false,
      severity: "success",
      summary: "Model",
      detail: "Success",
      life: 3000
    })
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

const galleryDialogShow = ref(false);
const ModelGallery = reactive({
  model: {},
  gallery: [],
  uploads: []
})
const galleryDialog = (model)=>{
  galleryDialogShow.value = !galleryDialogShow.value
  ModelGallery.model = autobanModels.value.data.find(x=> x.id === model.id)
  ModelGallery.uploads = []
  ModelGallery.gallery = []
}
const onSelectedFiles = (e) => {
  ModelGallery.uploads = e.files
  for (let i = 0; i < e.files.length; i++) {
    const v = e.files[i]
    const reader = new FileReader()
    reader.onload = async ()=>{
      ModelGallery.gallery.push(reader.result)
    }
    reader.readAsDataURL(v)
  }
}
const clearGallery = () => {
  ModelGallery.uploads = []
  ModelGallery.gallery = []
}
const clearImage = (index) => {
  ModelGallery.uploads.splice(index, 1)
  ModelGallery.gallery.splice(index, 1)
}
const uploadNewCarGallery = async (e) => {
  loading.value = true
  await AutobanStore.handleAutobanModelGallery(ModelGallery)
  toast.add({
    closable: false,
    severity: "success",
    summary: "Images",
    detail: "has been Uploaded",
    life: 3000
  })
  loading.value = false
  ModelGallery.uploads = []
  ModelGallery.gallery = []
  ModelGallery.model = autobanModels.value.data.find(x=> x.id === ModelGallery.model.id)
}
const index = ref(null)
const activeIndex = ref(0)
const displayCustom = ref(false)
const imageClick = (index) => {
  activeIndex.value = index;
  displayCustom.value = true;
}

const deleteImage = (event,img) => {
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
        await AutobanStore.distroyAutobanModelGallery(img)
        toast.add({
          closable: false,
          severity: "success",
          summary: "Image",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Image",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

</script>

<style scoped>
::v-deep(.p-column-filter-clear-button) {
  display: none !important;
}
::v-deep(.p-dropdown-clear-icon) {
  top: 51% !important;
}


</style>
