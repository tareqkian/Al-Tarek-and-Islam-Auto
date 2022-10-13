<template>
  <PageLayout :meta="this.$route.meta">
    <div class="btn-group mb-2" role="group">
      <button v-if="$can(`add_options_classes`)"
              class="btn btn-primary"
              @click="classDialog()">
        <i class="fe fe-plus"></i>
        Add Class
      </button>
      <button v-if="$can(`add_options_sub_classes`)"
              class="btn btn-primary"
              @click="subClassDialog()">
        <i class="fe fe-plus"></i>
        Add Sub Class
      </button>
      <button v-if="$can(`add_options_categories`)"
              class="btn btn-primary"
              @click="categoryDialog()">
        <i class="fe fe-plus"></i>
        Add Category
      </button>
      <button v-if="$can(`add_options_options`)"
              class="btn btn-primary"
              @click="optionDialog()">
        <i class="fe fe-plus"></i>
        Add Option
      </button>
    </div>
    <div class="row">
      <div class="col-sm-12 col-xl">
        <div class="card">
          <Loading v-if="optionClasses.loading" />
          <draggable
            v-else
            v-model="optionClasses.data"
            group="option_sub_class"
            tag="div"
            handle=".feather-move"
            :component-data="{'role': 'tablist', 'aria-orientation': 'vertical'}"
            class="nav flex-column admisetting-tabs"
            @sort="classOrder"
            item-key="id">
            <template #item="{element}">
              <a class="nav-link d-flex justify-content-between"
                 data-bs-toggle="pill" @click="optionClassPane(element)" href="#classesPane" role="tab">
                <div>
                  <i class="feather-move p-1" style="cursor: move"></i> {{ t(element,"option_class_title") }}
                </div>
                <div v-if="$can(`edit_options_classes`) || $can(`delete_options_classes`)">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_options_classes`)"
                     @click="classDialog(element)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_options_classes`)"
                     @click="classDelete($event,element)"></i>
                </div>
              </a>
            </template>
          </draggable>
        </div>
      </div>
      <div class="col-sm-12 col-xl">
        <div class="tab-content">
          <div class="tab-pane" id="classesPane" role="tabpanel">
            <div class="card">
              <Loading v-if="optionSubClass.loading" />
              <draggable
                v-else
                v-model="optionSubClass.data"
                group="option_sub_class"
                tag="div"
                handle=".feather-move"
                :component-data="{'role': 'tablist', 'aria-orientation': 'vertical'}"
                class="nav flex-column admisetting-tabs"
                @sort="subClassOrder"
                item-key="id">
                <template #item="{element}">
                  <a class="nav-link d-flex justify-content-between"
                     data-bs-toggle="pill" @click="optionSubClassPane(element)" href="#subClassesPane" role="tab">
                    <div>
                      <i class="feather-move p-1" style="cursor: move"></i> {{ t(element,"option_sub_class_title") }}
                    </div>
                    <div v-if="$can(`edit_options_sub_classes`) || $can(`delete_options_sub_classes`)">
                      <i class="fa fa-edit text-info mx-1"
                         v-if="$can(`edit_options_sub_classes`)"
                         @click="subClassDialog(element)"></i>
                      <i class="fa fa-trash text-danger mx-1"
                         v-if="$can(`delete_options_sub_classes`)"
                         @click="subClassDelete($event,element)"></i>
                    </div>
                  </a>
                </template>
              </draggable>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <div class="tab-content">
          <div class="tab-pane" id="subClassesPane">
            <div class="card">
              <Loading v-if="optionCategory.loading" />
              <draggable
                v-else
                v-model="optionCategory.data"
                group="option_category"
                tag="div"
                handle=".feather-move"
                :component-data="{'role': 'tablist', 'aria-orientation': 'vertical'}"
                class="nav flex-column admisetting-tabs"
                @sort="categoryOrder"
                item-key="id">
                <template #item="{element}">
                  <a class="nav-link d-flex justify-content-between"
                     data-bs-toggle="pill" @click="optionCategoryPane(element)" href="#categoriesPane" role="tab">
                    <div>
                      <i class="feather-move handle p-1" style="cursor: move"></i> {{ t(element,"option_category_title") }}
                      <small class="text-muted ms-3"> {{ element.abbreviation }} </small>
                    </div>
                    <div v-if="$can(`edit_options_categories`) || $can(`delete_options_categories`)">
                      <i class="fa fa-edit text-info mx-1"
                         v-if="$can(`edit_options_categories`)"
                         @click="categoryDialog(element)"></i>
                      <i class="fa fa-trash text-danger mx-1"
                         v-if="$can(`delete_options_categories`)"
                         @click="categoryDelete($event,element)"></i>
                    </div>
                  </a>
                </template>
              </draggable>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="tab-content">
          <div class="tab-pane" id="categoriesPane" role="tabpanel">
            <div class="card">
              <Loading v-if="option.loading" />
              <draggable
                v-else
                v-model="option.data"
                group="option"
                tag="div"
                handle=".feather-move"
                class="row px-3"
                @sort="optionOrder"
                item-key="id">
                <template #item="{element}">
                  <div class="col-12 p-4 d-flex justify-content-between">
                    <div>
                      <i class="feather-move handle p-1" style="cursor: move"></i> {{ t(element,'option_title') }}
                      <small class="text-muted ms-3"> {{ element.abbreviation }} </small>
                    </div>
                    <div v-if="$can(`edit_options_options`) || $can(`delete_options_options`)">
                      <i class="fa fa-edit text-info mx-1"
                         v-if="$can(`edit_options_options`)"
                         @click="optionDialog(element)"></i>
                      <i class="fa fa-trash text-danger mx-1"
                         v-if="$can(`delete_options_options`)"
                         @click="optionDelete($event,element)"></i>
                    </div>
                  </div>
                </template>
              </draggable>
            </div>
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

const optionClasses = computed(()=>OptionStore.optionClasses)
OptionStore.initOptionClasses()

const optionSubClass = computed(()=>OptionStore.optionSubClass)
const optionClassPane = (optionClass) => {
  OptionStore.optionCategory.data = []
  OptionStore.option.data = []
  OptionStore.initOptionSubClass(optionClass)
}
const optionCategory = computed(()=>OptionStore.optionCategory)
const optionSubClassPane = (optionSubClass) => {
  OptionStore.option.data = []
  OptionStore.initOptionCategory(optionSubClass)
}
const option = computed(()=>OptionStore.option)
const optionCategoryPane = (optionCategory) => {
  OptionStore.initOption(optionCategory)
}

const optionSubClasses = computed(()=>OptionStore.optionSubClasses)
const optionCategories = computed(()=>OptionStore.optionCategories)
const options = computed(()=>OptionStore.options)

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
const classOrder = async (e)=>{
  optionClasses.value.data = optionClasses.value.data.map((v,i)=>{v.order = i;return v;})
  await OptionStore.handleOptionClasses({order: optionClasses.value.data})
  toast.add({
    closable: false,
    severity: "success",
    summary: "Class",
    detail: "has been Ordered",
    life: 3000
  })
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
  /*OptionStore.initOptionClasses()*/
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
const subClassOrder = async (e)=>{
  optionSubClass.value.data = optionSubClass.value.data.map((v,i)=>{v.order = i;return v;})
  await OptionStore.handleOptionSubClasses({order: optionSubClass.value.data})
  toast.add({
    closable: false,
    severity: "success",
    summary: "Sub Class",
    detail: "has been Ordered",
    life: 3000
  })
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
  OptionStore.initOptionSubClasses()
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
const categoryOrder = async (e)=>{
  optionCategory.value.data = optionCategory.value.data.map((v,i)=>{v.order = i;return v;})
  await OptionStore.handleOptionCategories({order: optionCategory.value.data})
  toast.add({
    closable: false,
    severity: "success",
    summary: "Categories",
    detail: "has been Ordered",
    life: 3000
  })
}

// Option
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
const optionOrder = async (e)=>{
  option.value.data = option.value.data.map((v,i)=>{v.order = i;return v;})
  await OptionStore.handleOptions({order: option.value.data})
  toast.add({
    closable: false,
    severity: "success",
    summary: "Options",
    detail: "has been Ordered",
    life: 3000
  })
}

/*
Echo.channel("OptionsEvent")
  .listen('OptionClassesAdder',({optionClass})=>{
    OptionStore.optionClasses.data = [
      ...OptionStore.optionClasses.data,
      {...optionClass}
    ]
  })
  .listen('OptionClassesEditor',({optionClass})=>{
    const classIndex = OptionStore.optionClasses.data.findIndex(x => x.id === optionClass.id);
    OptionStore.optionClasses.data[classIndex] = {...optionClass}
  })
  .listen('OptionClassesDeleter',({optionClass})=>{
    OptionStore.optionClasses.data = OptionStore.optionClasses.data.filter(x=> +x.id !== +optionClass.id)
  })

  .listen('OptionSubClassesAdder',({optionSubClass})=>{
    OptionStore.optionSubClasses.data = [...OptionStore.optionSubClasses.data, {...optionSubClass}]
    if ( OptionStore.optionSubClass.data.length && +OptionStore.optionSubClass.data[0].option_class_id === +optionSubClass.option_class_id )
      OptionStore.optionSubClass.data = [...OptionStore.optionSubClass.data, {...optionSubClass}]
  })
  .listen('OptionSubClassesEditor',({optionSubClass})=>{
    if ( OptionStore.optionSubClasses.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === optionSubClass.id);
      OptionStore.optionSubClasses.data = [
        ...OptionStore.optionSubClasses.data.slice(0,optionIndex),
        {...optionSubClass},
        ...OptionStore.optionSubClasses.data.slice(optionIndex+1)
      ]
    }
    if ( OptionStore.optionSubClass.data.length ) {
      const optionIndex = OptionStore.optionSubClass.data.findIndex(x => x.id === optionSubClass.id);
      OptionStore.optionSubClass.data[optionIndex] = {...optionSubClass}
    }
  })
  .listen('OptionSubClassesDeleter',({optionSubClass})=>{
      OptionStore.optionSubClasses.data = OptionStore.optionSubClasses.data.filter(x=> +x.id !== +optionSubClass.id)
      OptionStore.optionSubClass.data = OptionStore.optionSubClass.data.filter(x=> +x.id !== +optionSubClass.id)
  })

  .listen('OptionCategoryAdder',({optionCategory})=>{
    OptionStore.optionCategories.data = [...OptionStore.optionCategories.data, {...optionCategory}]
    if ( OptionStore.optionCategory.data.length && +OptionStore.optionCategory.data[0].option_sub_class_id === +optionCategory.option_sub_class_id )
      OptionStore.optionCategory.data = [...OptionStore.optionCategory.data, {...optionCategory}]
  })
  .listen('OptionCategoryEditor',({optionCategory})=>{
    if ( OptionStore.optionCategories.data.length ) {
      const optionIndex = OptionStore.optionCategories.data.findIndex(x => x.id === optionCategory.id);
      OptionStore.optionCategories.data[optionIndex] = {...optionCategory}
    }
    if ( OptionStore.optionCategory.data.length ) {
      const optionIndex = OptionStore.optionCategory.data.findIndex(x => x.id === optionCategory.id);
      OptionStore.optionCategory.data[optionIndex] = {...optionCategory}
    }
  })
  .listen('OptionCategoryDeleter',({optionCategory})=>{
      OptionStore.optionCategories.data = OptionStore.optionCategories.data.filter(x=> +x.id !== +optionCategory.id)
      OptionStore.optionCategory.data = OptionStore.optionCategory.data.filter(x=> +x.id !== +optionCategory.id)
  })

  .listen('OptionAdder',({option})=>{
    OptionStore.options.data = [...OptionStore.options.data, option]
    if ( OptionStore.option.data.length && +OptionStore.option.data[0].option_category_id === +option.option_category_id )
      OptionStore.option.data = [...OptionStore.option.data, option]
  })
  .listen('OptionEditor',({option})=>{
    if ( OptionStore.options.data.length ) {
      const optionIndex = OptionStore.options.data.findIndex(x => x.id === option.id);
      OptionStore.options.data[optionIndex] = {...option}
    }
    if ( OptionStore.option.data.length ) {
      const optionIndex = OptionStore.option.data.findIndex(x => x.id === option.id);
      OptionStore.option.data[optionIndex] = {...option}
    }
  })
  .listen('OptionDeleter',({option})=>{
      OptionStore.options.data = OptionStore.options.data.filter(x=> +x.id !== +option.id)
      OptionStore.option.data = OptionStore.option.data.filter(x=> +x.id !== +option.id)
  })
*/
</script>

