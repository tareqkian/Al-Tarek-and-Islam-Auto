<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <button v-if="$can(`add_${route.meta.permissionsLayout}`)"
                class="btn btn-primary mb-2 me-3"
                @click="classDialog()">
          <i class="fe fe-plus"></i>
          Add Class
        </button>
        <button v-if="$can(`add_${route.meta.permissionsLayout}`)"
                class="btn btn-primary mb-2 me-3"
                @click="subClassDialog()">
          <i class="fe fe-plus"></i>
          Add Sub Class
        </button>
        <div class="card">
          <div class="card-body">
            <DataTable :loading="optionSubClasses.loading"
                       :value="optionSubClasses.data"
                       :filters="filters"
                       :rows-per-page-options="[5,15,30]"

                       rowGroupMode="rowspan"
                       groupRowsBy="option_class.option_class_title"
                       sortMode="single"
                       sortField="option_class.option_class_title"
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

              <Column field="option_class.option_class_title" header="Class title">
                <template #body="value">
                  <span class="mx-2">{{ t(value.data.option_class,'option_class_title') }}</span>
                  <div class="d-inline-block float-end">
                    <i class="fa fa-edit text-info mx-1"
                       v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                       @click="classDialog(value.data.option_class)"></i>
                    <i class="fa fa-trash text-danger mx-1"
                       v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                       @click="classDelete($event,value.data.option_class)"></i>
                  </div>
                </template>
              </Column>
              <Column field="option_sub_class_title" header="Sub Class Title">
                <template #body="value">
                  {{ t(value.data,'option_sub_class_title') }}
                </template>
              </Column>
              <Column v-if="$can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                     @click="subClassDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                     @click="subClassDelete($event,val.data)"></i>
                </template>
              </Column>

              <!--
                            <Column field="model_image" header="model Image">
                              <template #body="val">
                                <Image width="80" :src="val.data.model_image" preview/>
                              </template>
                            </Column>
              -->
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
      v-model:visible="classDialogShow">
      <form @submit.prevent="handleOptionClass">
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedClass.en.option_class_title"
                 :class="[errors['en.option_class_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Option Class Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.option_class_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedClass.ar.option_class_title"
                 :class="[errors['ar.option_class_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Option Class Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.option_class_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="classDialogShow = !classDialogShow">Close</button>
        </div>
      </form>
    </Dialog>
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="subClassDialogShow">

      <form @submit.prevent="handleOptionSubClass">
        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedSubClass.option_class_id"
            :options="optionClasses.data"
            :loading="optionClasses.loading"
            filter filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'option_class_title')"
            class="form-control d-flex align-items-stretch"
            :class="[!errors.option_class_id || 'is-invalid']"
            placeholder="Class" />
          <label> Class </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.option_class_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedSubClass.en.option_sub_class_title"
                 :class="[errors['en.option_sub_class_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Option Sub Class Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.option_sub_class_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedSubClass.ar.option_sub_class_title"
                 :class="[errors['ar.option_sub_class_title'] ? 'is-invalid' : '']"
                 placeholder="Brand Title">
          <label> Option Sub Class Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.option_sub_class_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="subClassDialogShow = !subClassDialogShow">Close</button>
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
import Image from "primevue/image";
import Dropdown from "primevue/dropdown"
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, reactive, ref} from "vue";
import {FilterMatchMode} from "primevue/api";
import {useOptionStore} from "../../store/Options";
import {useRoute} from "vue-router";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();
const route = useRoute()

const OptionStore = useOptionStore()
const optionClasses = computed(()=>OptionStore.optionClasses)

const optionSubClasses = computed(()=>OptionStore.optionSubClasses)
OptionStore.initOptionSubClasses()

const errors = ref([])
const loading = ref(false);

const classDialogShow = ref(false)
const subClassDialogShow = ref(false)

const selectedClassOriginal = {
  en: {option_class_title: null},
  ar: {option_class_title: null}
}
const selectedClass = reactive({...selectedClassOriginal})
const selectedClassReset = ()=>Object.assign(selectedClass,selectedClassOriginal)

const selectedSubClassOriginal = {
  option_class_id: null,
  en: {option_sub_class_title: null},
  ar: {option_sub_class_title: null}
}
const selectedSubClass = reactive({...selectedSubClassOriginal})
const selectedSubClassReset = ()=>Object.assign(selectedSubClass,selectedSubClassOriginal)

const classDialog = (optionClass = {})=>{
  selectedClassReset()
  errors.value = []
  classDialogShow.value = !classDialogShow.value
  Object.assign(selectedClass,{
    id: (optionClass.id || false),
    en: { option_class_title: t(optionClass,'option_class_title','en') || null },
    ar: { option_class_title: t(optionClass,'option_class_title','ar') || null },
  })
}
const subClassDialog = (optionSubClass = {})=>{
  OptionStore.initOptionClasses()
  selectedSubClassReset()
  errors.value = []
  subClassDialogShow.value = !subClassDialogShow.value
  console.log(optionSubClass)
  Object.assign(selectedSubClass,{
    id: optionSubClass.id || null,
    option_class_id: optionSubClass.option_class ? optionSubClass.option_class.id : null,
    en: {option_sub_class_title: t(optionSubClass,'option_sub_class_title','en') || null},
    ar: {option_sub_class_title: t(optionSubClass,'option_sub_class_title','ar') || null}
  })
}

const handleOptionClass = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptionClasses(selectedClass)
    classDialogShow.value = !classDialogShow.value
    if ( !selectedClass.id ) {
      OptionStore.initOptionClasses()
      subClassDialogShow.value = !subClassDialogShow.value
    }
    toast.add({
      closable: false,
      severity: "success",
      summary: "Class",
      detail: "Success",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const handleOptionSubClass = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptionSubClasses(selectedSubClass)
    subClassDialogShow.value = !subClassDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "Sub Class",
      detail: "Success",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}

const classDelete = (event,optionClass)=>{
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
        await OptionStore.distroyOptionClasses(optionClass)
        toast.add({
          closable: false,
          severity: "success",
          summary: "Class",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Class",
          detail: e,
          life: 3000
        })
      }
    }
  });
}
const subClassDelete = (event,optionSubClass)=>{
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
        await OptionStore.distroyOptionSubClasses(optionSubClass)
        toast.add({
          closable: false,
          severity: "success",
          summary: "Sub Class",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "Sub Class",
          detail: e,
          life: 3000
        })
      }
    }
  });
}


/*
Echo.channel('ClassesEvent')
  .listen('OptionClassesAdder',({optionClass})=>{
    OptionStore.optionClasses.data = [
      ...OptionStore.optionClasses.data,
      {...optionClass}
    ]
  })
  .listen('OptionClassesEditor',({optionClass})=>{
    const classIndex = OptionStore.optionClasses.data.findIndex(x => x.id === optionClass.id);
    OptionStore.optionClasses.data = [
      ...OptionStore.optionClasses.data.slice(0,classIndex),
      {...optionClass},
      ...OptionStore.optionClasses.data.slice(classIndex+1)
    ]
    let subClasses = OptionStore.optionSubClasses.data.filter(x => x.option_class.id === optionClass.id);
    for (let i = 0; i < subClasses.length; i++) {
      const x = subClasses[i];
      const subClassIndex = OptionStore.optionSubClasses.data.findIndex(z => z.id === x.id);
      OptionStore.optionSubClasses.data[subClassIndex].option_class = {...optionClass}
    }
  })
  .listen('OptionClassesDeleter',({optionClass})=>{
    const classIndex = OptionStore.optionClasses.data.findIndex(x => x.id === optionClass.id);
    OptionStore.optionClasses.data = [
      ...OptionStore.optionClasses.data.slice(0,classIndex),
      ...OptionStore.optionClasses.data.slice(classIndex+1)
    ]
    let subClasses = OptionStore.optionSubClasses.data.filter(x => x.option_class.id === optionClass.id);
    for (let i = 0; i < subClasses.length; i++) {
      const x = subClasses[i];
      const subClassIndex = OptionStore.optionSubClasses.data.findIndex(z => z.id === x.id);
      OptionStore.optionSubClasses.data = [
        ...OptionStore.optionSubClasses.data.slice(0,subClassIndex),
        ...OptionStore.optionSubClasses.data.slice(subClassIndex+1)
      ]
    }
  })

  .listen('OptionSubClassesAdder',({optionSubClass})=>{
    OptionStore.optionSubClasses.data = [
      ...OptionStore.optionSubClasses.data,
      {...optionSubClass}
    ]
  })
  .listen('OptionSubClassesEditor',({optionSubClass})=>{
    const classIndex = OptionStore.optionSubClasses.data.findIndex(x => x.id === optionSubClass.id);
    OptionStore.optionSubClasses.data = [
      ...OptionStore.optionSubClasses.data.slice(0,classIndex),
      {...optionSubClass},
      ...OptionStore.optionSubClasses.data.slice(classIndex+1)
    ]
  })
  .listen('OptionSubClassesDeleter',({optionSubClass})=>{
    const classIndex = OptionStore.optionSubClasses.data.findIndex(x => x.id === optionSubClass.id);
    OptionStore.optionSubClasses.data = [
      ...OptionStore.optionSubClasses.data.slice(0,classIndex),
      ...OptionStore.optionSubClasses.data.slice(classIndex+1)
    ]
  })

*/
</script>
