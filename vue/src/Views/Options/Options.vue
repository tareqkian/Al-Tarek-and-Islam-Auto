<template>
  <PageLayout :meta="this.$route.meta">
    <div class="row">
      <div class="col">
        <button type="button" v-if="$can(`add_${this.$route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="optionDialog()">
          <i class="fe fe-plus"></i>
          Add Option
        </button>
<!--        <div class="card">-->
<!--          <div class="card-body">-->
        <Loading v-if="optionClasses.loading" />
        <div v-else class="accordion" id="accordionFlush">
          <draggable
            v-model="optionClasses.data"
            group="option_class"
            tag="div"
            class="m-0 p-0 card rounded"
            item-key="id">
            <template #item="{element}">
              <div class="py-3 border-bottom">
                <div class="row justify-content-center px-3"
                     data-bs-toggle="collapse"
                     :data-bs-target="`#flush-collapse-${element.option_class_title}`">
                  <div class="col-12 text-center">
                    <span class="d-block mt-2 font-weight-bold">
                      {{ t(element,'option_class_title') }}




                      <i class="fa fa-edit text-success mx-1"
                         v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                         @click.passive="classDialog(element)"></i>





                      <i class="fa fa-trash text-danger mx-1"
                         v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                         @click="classDelete($event,element)"></i>
                    </span>
                  </div>
                </div>
                <div :id="`flush-collapse-${element.option_class_title}`" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
                  <div class="accordion-body">
                    <div class="accordion" :id="`accordionFlush${element.option_class_title}`">
                      <draggable
                        @change="log"
                        :list="ref(optionSubClasses.data.filter(x=>x.option_class_id === element.id)).value"
                        group="option_sub_class"
                        tag="div"
                        class="m-0 p-0 row"
                        item-key="id">
                        <template #item="{element}">
                          <div class="col-6 py-3 border-bottom">
                            <div class="row justify-content-center px-3"
                                 data-bs-toggle="collapse"
                                 :data-bs-target="`#flush-collapse-${element.option_sub_class_title}`">
                              <div class="col-12 text-center">
                                <span class="d-block mt-2 font-weight-bold">
                                  {{ t(element,'option_sub_class_title') }}
                                  <i class="fa fa-edit text-success mx-1"
                                     v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                                     @click="subClassDialog(element)"></i>
                                  <i class="fa fa-trash text-danger mx-1"
                                     v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                                     @click="subClassDelete($event,element)"></i>
                                </span>
                              </div>
                            </div>
                            <div :id="`flush-collapse-${element.option_sub_class_title}`" class="accordion-collapse collapse" :data-bs-parent="`#accordionFlush${element.option_class_title}`">
                              <div class="accordion-body">
                                <draggable
                                  :list="element.children"
                                  group="option_category"
                                  tag="div"
                                  class="row"
                                  item-key="id">
                                  <template #item="{element}">
                                    <div class="col-12 py-2 border-bottom border-top d-flex justify-content-between">
                                      <span>{{ `${t(element,'option_category_title')} ${element.abbreviation ? ' - '+element.abbreviation : ''}` }}</span>
                                      <span>
                                        <i class="fa fa-eye text-info mx-1"
                                           v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                                           @click="optionsDialog(element)"></i>
                                        <i class="fa fa-edit text-success mx-1"
                                           v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                                           @click="categoryDialog(element)"></i>
                                        <i class="fa fa-trash text-danger mx-1"
                                           v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                                           @click="categoryDelete($event,element)"></i>
                                      </span>
                                    </div>
                                  </template>
                                </draggable>
                              </div>
                            </div>
                          </div>
                        </template>
                      </draggable>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </draggable>
        </div>
        <!--
                    <DataTable :loading="option.loading"
                               :value="option.data"
                               :filters="filters"
                               rowGroupMode="rowspan"
                               groupRowsBy="option_category.option_category_title"
                               sortMode="single"
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

                      <Column field="option_category.option_category_title" header="Category">
                        <template #body="value">
                          {{ t(value.data.option_category,'option_category_title') }}
                        </template>
                      </Column>

                      <Column field="option_title" header="Option Title">
                        <template #body="value">
                          {{ t(value.data,'option_title') }}
                        </template>
                      </Column>
                      <Column field="abbreviation" header="abbreviation">
                        <template #body="value">
                          {{ t(value.data,'abbreviation') }}
                        </template>
                      </Column>
                      <Column v-if="$can(`edit_${this.$route.meta.permissionsLayout}`) || $can(`delete_${this.$route.meta.permissionsLayout}`)" header="Actions">
                        <template #body="val">
                          <i class="fa fa-edit text-info mx-1"
                             v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                             @click="optionDialog(val.data)"></i>
                          <i class="fa fa-trash text-danger mx-1"
                             v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                             @click="optionDelete($event,val.data)"></i>
                        </template>
                      </Column>

                      <template #footer v-if="option.pagination">
                        <Paginator :rows="+option.pagination.per_page"
                                   :totalRecords="option.pagination.total"
                                   :rowsPerPageOptions="[10,20,30]"
                                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                                   current-page-report-template="Showing {first} to {last} of {totalRecords}"
                                   @page="OptionStore.initOptions($event)"></Paginator>
                      </template>
                    </DataTable>
        -->
<!--          </div>-->
<!--        </div>-->
      </div>
    </div>

    <br>
    <br>
    <br>
    <pre>
      {{ optionSubClasses.data.map(x=>x.option_sub_class_title) }}
    </pre>

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
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="categoryDialogShow">
      <form @submit.prevent="handleCategory">

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedCategory.option_sub_class_id"
            :options="optionSubClasses.data"
            :loading="optionSubClasses.loading"
            filter filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'option_sub_class_title')"
            class="form-control d-flex align-items-stretch"
            :class="[!errors.option_sub_class_id || 'is-invalid']"
            placeholder="Sub Class" />
          <label> Sub Class </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.option_sub_class_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedCategory.en.option_category_title"
                 :class="[errors['en.option_category_title'] ? 'is-invalid' : '']"
                 placeholder="category Title">
          <label> category Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.option_category_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedCategory.ar.option_category_title"
                 :class="[errors['ar.option_category_title'] ? 'is-invalid' : '']"
                 placeholder="category Title">
          <label> category Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.option_category_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedCategory.abbreviation"
                 :class="[errors.abbreviation ? 'is-invalid' : '']"
                 placeholder="category Title">
          <label> Abbreviation </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.abbreviation" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedCategory.input_type"
            :options="inputTypes"
            filter filter-placeholder="Search"
            class="form-control d-flex align-items-stretch"
            :class="[!errors.input_type || 'is-invalid']"
            placeholder="Input Type" />
          <label> Input Type </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.input_type" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2" v-if="selectedCategory.input_type === 'number'">
          <input class="form-control"
                 v-model="selectedCategory.number_format"
                 :class="[errors.number_format ? 'is-invalid' : '']"
                 placeholder="category Title">
          <label> Number Format </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.number_format" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="categoryDialogShow = !categoryDialogShow">Close</button>
        </div>
      </form>
    </Dialog>
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="optionsDialogShow">
      <draggable
        v-model="selectedOptions"
        group="option_category"
        tag="div"
        class="row"
        item-key="id">
        <template #item="{element}">
          <div class="col-12 py-2 border-bottom border-top d-flex justify-content-between">
            <span>{{ t(element,'option_title') }}</span>
            <span>
              <i class="fa fa-edit text-success mx-1"
                 v-if="$can(`edit_${this.$route.meta.permissionsLayout}`)"
                 @click="optionDialog(element)"></i>
              <i class="fa fa-trash text-danger mx-1"
                 v-if="$can(`delete_${this.$route.meta.permissionsLayout}`)"
                 @click="optionDelete($event,element)"></i>
            </span>
          </div>
        </template>
      </draggable>
    </Dialog>
    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="optionDialogShow">
      <form @submit.prevent="handleOptions">

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedOption.option_category_id"
            :options="optionCategories.data"
            :loading="optionCategories.loading"
            filter filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'option_category_title')"
            class="form-control d-flex align-items-stretch"
            :class="[!errors.option_category_id || 'is-invalid']"
            placeholder="Category" />
          <label> Category </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.option_category_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedOption.en.option_title"
                 :class="[errors['en.option_title'] ? 'is-invalid' : '']"
                 placeholder="option Title">
          <label> option Title EN </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['en.option_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedOption.ar.option_title"
                 :class="[errors['ar.option_title'] ? 'is-invalid' : '']"
                 placeholder="option Title">
          <label> option Title AR </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors['ar.option_title']" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 v-model="selectedOption.abbreviation"
                 :class="[errors.abbreviation ? 'is-invalid' : '']"
                 placeholder="option Title">
          <label> Abbreviation </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.abbreviation" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="optionDialogShow = !optionDialogShow">Close</button>
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
import Dropdown from "primevue/dropdown";
import numeral from "numeral";
import Paginator from 'primevue/paginator';
import Tree from '../../components/Options/Tree/Tree.vue'
import draggable from 'vuedraggable'

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, reactive, ref} from "vue";
import {useOptionStore} from "../../store/Options";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})

const t = inject("t");
const toast = useToast();
const confirm = useConfirm();

const expandedRowGroups = ref(null)
const OptionStore = useOptionStore()

// const options = computed(()=>OptionStore.options)
// OptionStore.initOptions()

const optionClasses = computed(()=>OptionStore.optionClasses)
OptionStore.initOptionClasses()

const optionSubClasses = computed(()=>OptionStore.optionSubClasses)
OptionStore.initOptionSubClasses()

const optionCategories = computed(()=>OptionStore.optionCategories)
OptionStore.initOptionCategories()

const log = (e)=>{
  console.log(e.moved)
}

const errors = ref([])
const loading = ref(false);

// Class
const classDialogShow = ref(false)
const selectedClassOriginal = {
  en: {option_class_title: null},
  ar: {option_class_title: null}
}
const selectedClass = reactive({...selectedClassOriginal})
const selectedClassReset = ()=>Object.assign(selectedClass,selectedClassOriginal)
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
const handleOptionClass = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptionClasses(selectedClass)
    classDialogShow.value = !classDialogShow.value
    if ( !selectedClass.id ) {
      // OptionStore.initOptionClasses()
      subClassDialogShow.value = !subClassDialogShow.value
    }
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

// Sub Class
const subClassDialogShow = ref(false)
const selectedSubClassOriginal = {
  option_class_id: null,
  en: {option_sub_class_title: null},
  ar: {option_sub_class_title: null}
}
const selectedSubClass = reactive({...selectedSubClassOriginal})
const selectedSubClassReset = ()=>Object.assign(selectedSubClass,selectedSubClassOriginal)
const subClassDialog = (optionSubClass = {})=>{
  // OptionStore.initOptionClasses()
  selectedSubClassReset()
  errors.value = []
  subClassDialogShow.value = !subClassDialogShow.value
  Object.assign(selectedSubClass,{
    id: optionSubClass.id || null,
    option_class_id: optionSubClass.option_class_id || null,
    en: {option_sub_class_title: t(optionSubClass,'option_sub_class_title','en') || null},
    ar: {option_sub_class_title: t(optionSubClass,'option_sub_class_title','ar') || null}
  })
}
const handleOptionSubClass = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptionSubClasses(selectedSubClass)
    subClassDialogShow.value = !subClassDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
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

// Category
const inputTypes = ['text', 'number', 'select', 'multiple select']
const categoryDialogShow = ref(false)
const selectedCategoryOriginal = {
  id: null,
  option_sub_class_id: null,
  en: {option_category_title: null,},
  ar: {option_category_title: null,},
  abbreviation: null,
  input_type: null,
  number_format: null
}
const selectedCategory = reactive({...selectedCategoryOriginal})
const selectedCategoryReset = ()=>Object.assign(selectedCategory,selectedCategoryOriginal)
const categoryDialog = (category = {})=>{
  selectedCategoryReset()
  // OptionStore.initOptionSubClasses()
  errors.value = []
  categoryDialogShow.value = !categoryDialogShow.value
  Object.assign(selectedCategory,{
    id: category.id || null,
    option_sub_class_id: (category.option_sub_class_id || null),
    en:{option_category_title: t(category,'option_category_title','en') || null},
    ar:{option_category_title: t(category,'option_category_title','ar') || null},
    abbreviation: category.abbreviation || null,
    input_type: category.input_type || null,
    number_format: category.number_format || null
  })
}
const handleCategory = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptionCategories(selectedCategory)
    categoryDialogShow.value = !categoryDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const categoryDelete = (event,category)=>{
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
        await OptionStore.distroyOptionCategories(category)
        toast.add({
          closable: false,
          severity: "success",
          summary: "category",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "category",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

const optionsDialogShow = ref(false)
const selectedOptions = ref([])
const optionsDialog = (options)=>{
  optionsDialogShow.value = !optionsDialogShow.value
  selectedOptions.value = options.children
}

const optionDialogShow = ref(false)
const selectedOption = reactive({
  id: null,
  option_category_id: null,
  en: {option_title: null,},
  ar: {option_title: null,},
  abbreviation: null,
})
const optionDialog = (option = {})=>{
  OptionStore.initOptionCategories()
  errors.value = []
  optionDialogShow.value = !optionDialogShow.value
  Object.assign(selectedOption,{
    id: option.id || null,
    option_category_id: (option.option_category_id || null),
    en:{option_title: t(option,'option_title','en') || null},
    ar:{option_title: t(option,'option_title','ar') || null},
    abbreviation: option.abbreviation || null,
  })
}

const handleOptions = async()=>{
  try {
    errors.value = []
    loading.value = true
    await OptionStore.handleOptions(selectedOption)
    optionDialogShow.value = !optionDialogShow.value
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const optionDelete = (event,option)=>{
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
        await OptionStore.distroyOptions(option)
        toast.add({
          closable: false,
          severity: "success",
          summary: "option",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "option",
          detail: e,
          life: 3000
        })
      }
    }
  });
}

Echo.channel("OptionsEvent")
  .listen('OptionClassesAdder',({optionClass})=>{
    OptionStore.options.data = [...OptionStore.options.data, optionClass]
  })
  .listen('OptionClassesEditor',({optionClass})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === optionClass.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        {...optionClass},
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })
  .listen('OptionClassesDeleter',({optionClass})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === optionClass.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })

  .listen('OptionClassesAdder',({optionClass})=>{
    OptionStore.options.data = [...OptionStore.options.data, optionClass]
  })
  .listen('OptionClassesEditor',({optionClass})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === optionClass.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        {...optionClass},
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })
  .listen('OptionClassesDeleter',({optionClass})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === optionClass.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })

  .listen('OptionAdder',({option})=>{
    OptionStore.options.data = [...OptionStore.options.data, option]
  })
  .listen('OptionEditor',({option})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === option.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        {...option},
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })
  .listen('OptionDeleter',({option})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === option.id);
      OptionStore.options.data = [
        ...OptionStore.options.data.slice(0,optionIndex),
        ...OptionStore.options.data.slice(optionIndex+1)
      ]
    }
  })

</script>
