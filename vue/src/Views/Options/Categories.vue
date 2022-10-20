<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <button type="button" v-if="$can(`add_${route.meta.permissionsLayout}`)" class="btn btn-primary mb-2 me-3" @click="categoryDialog()">
          <i class="fe fe-plus"></i>
          Add Category
        </button>

        <div class="card">
          <div class="card-body">
            <DataTable :loading="optionCategories.loading"
                       :value="optionCategories.data"
                       :filters="filters"

                       rowGroupMode="rowspan"
                       groupRowsBy="option_sub_class.option_sub_class_title"
                       sortMode="single"
                       sortField="option_sub_class.option_sub_class_title"
                       :sortOrder="1"

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

              <Column field="option_sub_class.option_sub_class_title" header="Sub Class">
                <template #body="value">
                  {{ t(value.data.option_sub_class,'option_sub_class_title') }}
                </template>
              </Column>

              <Column field="option_category_title" header="Category Title">
                <template #body="value">
                  {{ t(value.data,'option_category_title') }}
                </template>
              </Column>
              <Column field="abbreviation" header="abbreviation">
                <template #body="value">
                  {{ t(value.data,'abbreviation') }}
                </template>
              </Column>
              <Column field="input_type" header="input type">
                <template #body="value">
                  {{ t(value.data,'input_type') }}
                </template>
              </Column>

              <Column field="number_format" header="number format">
                <template #body="value">
                  {{ t(value.data,'number_format') }}
                </template>
              </Column>

              <Column v-if="$can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`)" header="Actions">
                <template #body="val">
                  <i class="fa fa-edit text-info mx-1"
                     v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                     @click="categoryDialog(val.data)"></i>
                  <i class="fa fa-trash text-danger mx-1"
                     v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                     @click="categoryDelete($event,val.data)"></i>
                </template>
              </Column>


              <template #footer v-if="optionCategories.pagination">
                <Paginator :rows="+optionCategories.pagination.per_page"
                           :totalRecords="optionCategories.pagination.total"
                           :rowsPerPageOptions="[10,20,30]"
                           template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           current-page-report-template="Showing {first} to {last} of {totalRecords}"
                           @page="OptionStore.initOptionCategories($event)"></Paginator>
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

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Dialog from "primevue/dialog";
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Dropdown from "primevue/dropdown";
import numeral from "numeral";

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";

import {computed, inject, reactive, ref} from "vue";
import {useOptionStore} from "../../store/Options";
import {FilterMatchMode} from "primevue/api";
import {useRoute} from "vue-router";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();
const route = useRoute()

const expandedRowGroups = ref(null)
const OptionStore = useOptionStore()

const optionCategories = computed(()=>OptionStore.optionCategories)
OptionStore.initOptionCategories()
const optionSubClasses = computed(()=>OptionStore.optionSubClasses)

const errors = ref([])
const loading = ref(false);

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
    option_sub_class_id: (category.option_sub_class ? category.option_sub_class.id : null),
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
    toast.add({
      closable: false,
      severity: "success",
      summary: "Category",
      detail: "Success",
      life: 3000
    })
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

/*Echo.channel("CategoriesEvent")
  .listen('OptionCategoryAdder',({optionCategory})=>{
    OptionStore.optionCategories.data = [...OptionStore.optionCategories.data, optionCategory]
  })
  .listen('OptionCategoryEditor',({optionCategory})=>{
    if ( OptionStore.optionCategories.data.length ) {
      const categoryIndex = OptionStore.optionCategories.data.findIndex(x => x.id === optionCategory.id);
      OptionStore.optionCategories.data = [
        ...OptionStore.optionCategories.data.slice(0,categoryIndex),
        {...optionCategory},
        ...OptionStore.optionCategories.data.slice(categoryIndex+1)
      ]
    }
  })
  .listen('OptionCategoryDeleter',({optionCategory})=>{
    if ( OptionStore.optionCategories.data.length ) {
      const categoryIndex = OptionStore.optionCategories.data.findIndex(x => x.id === optionCategory.id);
      OptionStore.optionCategories.data = [
        ...OptionStore.optionCategories.data.slice(0,categoryIndex),
        ...OptionStore.optionCategories.data.slice(categoryIndex+1)
      ]
    }
  })*/

</script>
