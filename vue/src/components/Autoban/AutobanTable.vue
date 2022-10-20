<template>
  <DataTable :loading="autoban.loading"
             :value="autoban.data"
             :filters="filters"

             rowGroupMode="rowspan"
             groupRowsBy="model.model_title"
             sortMode="single"
             :sortField="opt => `${opt.model.brand.brand_title} - ${opt.model.model_title} - ${opt.year.year_number} - ${opt.order}`"
             :sortOrder="1"
             @rowReorder="emits('onRowReorder',$event)"

             table-class="table table-vcenter text-nowrap border-bottom table-striped table-hover">
    <template #loading>
      <Loading />
    </template>
    <template #header>
      <div class="row flex-row-reverse justify-content-center justify-content-md-start w-100 m-0">
        <div class="search-element col-10 col-md-3 mx-3 mb-4 p-0">
          <input type="search" class="form-control header-search"
                 v-model="filter"
                 placeholder="Search…"
                 aria-label="Search" tabindex="1">
          <i class="feather feather-search position-absolute" style="top: 30%;right: 10px"></i>
        </div>
      </div>
    </template>
    <template #empty>
      <p class="h5 text-center m-0 text-muted"> There is No Data </p>
    </template>

    <Column field="model.model_title" header="Model" headerStyle="width: 25%">
      <template #body="value">
        <Image width="50" :src="value.data.model.brand.brand_image" preview/>
        <Image width="80" :src="value.data.model.model_image" preview/>
        <span class="mx-2">
          {{ `${t(value.data.model.brand,'brand_title')} - ${t(value.data.model,'model_title')}` }}
        </span>
      </template>
    </Column>
    <Column :rowReorder="true" headerStyle="width: 3rem" :reorderableColumn="false" />
    <Column field="type" header="Type">
      <template #body="value">
        {{ t(value.data.type,'type_title') }}
      </template>
    </Column>
    <Column field="year" header="year">
      <template #body="value">
        {{ t(value.data.year,'year_number') }}
      </template>
    </Column>
    <Column v-if="type === 'autoban'" field="price.official" header="official">
      <template #body="value">
        {{ t(value.data.price,'official',null,true) }}
      </template>
    </Column>
    <Column v-if="type === 'autoban'" field="price.commercial" header="commercial">
      <template #body="value">
        {{ t(value.data.price,'commercial',null,true) }}
      </template>
    </Column>
    <Column v-if="type === 'autoban'" field="price.sale" header="sale">
      <template #body="value">
        {{ t(value.data.price,'sale',null,true) }}
      </template>
    </Column>
    <Column v-if="type === 'autoban'" field="price_list_appearance" header="appearance">
      <template #body="value">
        <div class="form-switch">
          <input class="form-check-input"
                 type="checkbox"
                 @change="emits('price_list_appearance_market_availability',value.data)"
                 v-model='value.data.price_list_appearance'>
        </div>
      </template>
    </Column>
    <Column v-if="type === 'autoban'" field="market_availability" header="availability">
      <template #body="value">
        <div class="form-switch">
          <input class="form-check-input"
                 type="checkbox"
                 @change="emits('price_list_appearance_market_availability',value.data)"
                 v-model='value.data.market_availability'>
        </div>
      </template>
    </Column>
    <Column v-if="type === 'options'" header="Options">
      <template #body="val">
        <i class="fa fa-edit text-info mx-1" @click="emits('autobanOptionDialog',val.data)"></i>
      </template>
    </Column>
    <Column v-if="type === 'autoban' && ($can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`))" header="Actions">
      <template #body="val">
        <i class="fa fa-edit text-info mx-1"
           v-if="$can(`edit_${route.meta.permissionsLayout}`)"
           @click="emits('autobanDialog',val.data)"></i>
        <i class="fa fa-trash text-danger mx-1"
           v-if="$can(`delete_${route.meta.permissionsLayout}`)"
           @click="emits('autobanDelete',$event,val.data)"></i>
      </template>
    </Column>

    <template #footer v-if="autoban.pagination">
      <Paginator :rows="+autoban.pagination.per_page"
                 :totalRecords="autoban.pagination.total"
                 :rowsPerPageOptions="[10,20,30]"
                 template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                 current-page-report-template="Showing {first} to {last} of {totalRecords}"
                 @page="emits('page',$event,filter)"></Paginator>
    </template>
  </DataTable>
</template>

<script setup>
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Dropdown from "primevue/dropdown";
import Image from "primevue/image";
import {watch} from "vue";
import _debounce from "lodash/debounce";
import {useRoute} from "vue-router";

const route = useRoute()

const props = defineProps({
  type: String || null,
  autoban: Object,
  filters: Object,
  filter: String
})
const emits = defineEmits([
  'onRowReorder',
  'page',
  'autobanDialog',
  'autobanDelete',
  'price_list_appearance_market_availability',
  'autobanOptionDialog'
])
watch(
  ()=>props.filter,
  _debounce((val)=>{
    emits('page', {},val)
  },400)
)
</script>
