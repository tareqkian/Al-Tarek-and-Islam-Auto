<template>

<!--
  <div class="row">
    <div class="col">
      <pre>{{ autoban.data.map(x=>x.model.brand.brand_title+" - "+x.model.model_title) }}</pre>
    </div>
    <div class="col">
      <pre>{{ autoban.data
        .map((x,index)=>{
          return {
            "name": x.model.brand.brand_title + " - " + x.model.model_title,
            "indexFrom": index,
            "indexTo": findLastIndex(autoban.data.map(y=>y.model.id),x.model.id,index),
          };
        }).filter((value, index, self)=>(index ? value.name !== self[index-1].name : true )) }}</pre>
    </div>
  </div>
-->


  <table class="table table-vcenter text-nowrap border-bottom table-striped table-hover position-relative dataTable">
    <div v-if="autoban.loading"
         class="position-absolute w-100 top-0 bottom-0 d-flex align-items-center"
         style="background: #00000050">
      <Loading />
    </div>
    <thead>
      <tr @click="trSorting($event)">
        <th class="sorting" data-sort="model">Model</th>
        <th style="width: 3rem" v-if="type === 'autoban'"></th>
        <th class="sorting" data-sort="type_title" style="width: 15rem">Type</th>
        <th class="sorting" data-sort="year_number" style="width: 6rem">Year</th>
        <th class="sorting" data-sort="specs_no" style="width: 10rem" v-if="type === 'options'">No. Of Specs</th>
        <th class="sorting" data-sort="specs_req" style="width: 10rem" v-if="type === 'options'">Required Specs</th>
        <th style="width: 7rem" v-if="type === 'options'">Review</th>
        <th class="sorting" data-sort="latestOptionUpdate" style="width: 15rem" v-if="type === 'options'">Last Update</th>
        <th style="width: 13rem" v-if="type === 'autoban' || type === 'prices'">Official</th>
        <th style="width: 13rem" v-if="type === 'prices'">Commercial</th>
        <th style="width: 13rem" v-if="type === 'prices'">Sale</th>
        <th style="width: 3rem;padding: 0 !important;" v-if="type === 'prices'"></th>
        <th style="width: 10rem" v-if="type === 'autoban'"> Appearance </th>
        <th style="width: 3rem" v-if="type === 'prices'"> Availability </th>
        <th style="width: 7rem" v-if="type === 'options'"> Insert / Edit </th>
        <th style="width: 7rem" v-if="type === 'autoban' && ($can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`))"> Actions </th>
        <th style="width: 7rem" v-if="type === 'prices'"></th>
      </tr>
    </thead>
    <thead>
      <tr>
        <th>
          <div class="row p-0 m-0">
            <div class="col-6 p-0 px-1">
              <Dropdown
                :options="pricelistBrands.data" :loading="pricelistBrands.loading"
                v-model="filtersCol.brand_title.value" placeholder="Brand"
                @change="clear('brand_title')" show-clear
                :option-label="opt=>t(opt,'brand_title')"
                filter filter-placeholder="Search"
                class="form-control d-flex align-items-stretch" />
            </div>
            <div class="col-6 p-0 px-1">
              <Dropdown
                :disabled="!filtersCol.brand_title.value"
                :options="pricelistModels.data.models" :loading="pricelistModels.loading"
                v-model="filtersCol.model_title.value" placeholder="Model"
                @change="clear('model_title')" show-clear
                :option-label="opt=>t(opt,'model_title')"
                filter filter-placeholder="Search"
                class="form-control d-flex align-items-stretch" />
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody v-for="item in autoban.data
        .map((x,index)=>{
            x.name = x.model.brand.brand_title + ' - ' + x.model.model_title
            x.indexFrom = index
            x.indexTo = findLastIndex(autoban.data.map(y=>y.model.id),x.model.id,index)
            return x;
          }).filter((value, index, self)=>(index ? value.name !== self[index-1].name : true ))" :key="item.id">
      <tr>
        <td style="width: 25%">
          <Image width="50" :src="item.model.brand.brand_image" preview/>
          <Image width="80" :src="item.model.model_image" preview/>
          <span class="mx-2"> {{ `${t(item.model.brand,'brand_title')} - ${t(item.model,'model_title')}` }} </span>
        </td>
        <td style="width: 75%" colspan="20" class="p-1">
          <table class="w-100">
            <draggable
              :list="ref(autoban.data.slice(item.indexFrom,item.indexTo)).value"
              @start="drag=true" @end="drag=false"
              :group="`model${item.model.id}`"
              tag="tbody" item-key="id"
              @change="onRowReorder"
              handle=".handle"
            >
              <template #item="{element}">
                <tr>
                  <td style="width: 3rem" v-if="type === 'autoban'" class="text-center"> <i class="pi pi-bars handle p-1" style="cursor: move"></i> </td>
                  <td style="width: 15rem"> {{ t(element.type,'type_title') }} </td>
                  <td style="width: 6rem"> {{ t(element.year,'year_number') }} </td>

                  <td style="width: 10rem" v-if="type === 'options'">{{ element.specs_no || '-' }}</td>
                  <td style="width: 10rem" v-if="type === 'options'"
                      :class="element.reviewed === element.pivotsOptionsRequired.count || 'text-warning'">
                    <span v-tooltip.right="{ value: element.pivotsOptionsRequired.data.map(x=>`${t(x,'option_category_title')} <small class='text-muted'>${x.abbreviation}</small>`).join('<br>'), escape: true}">
                      {{ element.pivotsOptionsRequired.count || '-' }}
                    </span>
                  </td>
                  <td style="width: 7rem" v-if="type === 'options'">
                    <div class="form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :checked="element.reviewed === element.pivotsOptionsRequired.count"
                        @change="emits('AutobanReviewed',$event.target.checked,element)"
                      />
                    </div>
                  </td>
                  <td style="width: 15rem" v-if="type === 'options'"> {{ element.latestOptionUpdate }} </td>

                  <td style="width: 13rem" v-if="type === 'autoban' || type === 'prices'">
                    <InputNumber v-if="type === 'prices'" v-model="element.price.official"
                                 input-class="form-control form-control-sm"
                                 mode="decimal" />
                    <span v-if="type === 'autoban'">{{ t(element.price,'official',null,true) }}</span>
                  </td>
                  <td style="width: 13rem" v-if="type === 'prices'">
                    <InputNumber
                      v-model="element.price.commercial"
                      input-class="form-control form-control-sm"
                      mode="decimal"
                    />
                  </td>
                  <td style="width: 13rem" v-if="type === 'prices'">
                    <InputNumber
                      v-model="element.price.sale"
                      input-class="form-control form-control-sm"
                      mode="decimal"
                    />
                  </td>
                  <td style="width: 3rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <i @click="emits('toggle',$event,element)" class="text-primary fa fa-calculator"></i>
                  </td>
                  <td style="width: 10rem" class="text-center" v-if="type === 'autoban'">
                    <div class="form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        @change="emits('price_list_appearance_market_availability',element)"
                        v-model='element.price_list_appearance'
                      />
                    </div>
                  </td>
                  <td style="width: 3rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <div class="form-switch px-2">
                      <input
                        class="form-check-input m-0"
                        type="checkbox"
                        @change="emits('price_list_appearance_market_availability',element)"
                        v-model='element.market_availability'
                      />
                    </div>
                  </td>
                  <td style="width: 7rem" v-if="type === 'options'">
                    <div class="form-switch">
                      <i class="fa fa-edit text-info mx-1" @click="emits('autobanOptionDialog',element)"></i>
                    </div>
                  </td>
                  <td style="width: 7rem" v-if="type === 'autoban' && ($can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`))">
                    <i class="fa fa-edit text-info mx-1"
                       v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                       @click="emits('autobanDialog',element)"></i>
                    <i class="fa fa-trash text-danger mx-1"
                       v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                       @click="emits('autobanDelete',$event,element)"></i>
                  </td>
                  <td style="width: 7rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <button @click.prevent="emits('priceChange',element)" type="submit" class="btn btn-sm btn-primary">Confirm</button>
                  </td>
                </tr>
              </template>
            </draggable>
          </table>
        </td>
      </tr>
    </tbody>
<!--    <tbody v-for="{ model } in autoban.data.filter((value, index, self)=>(index ? value.model.id !== self[index-1].model.id : true ))" :key="model.id">
      <tr>
        <td style="width: 25%">
          <Image width="50" :src="model.brand.brand_image" preview/>
          <Image width="80" :src="model.model_image" preview/>
          <span class="mx-2"> {{ `${t(model.brand,'brand_title')} - ${t(model,'model_title')}` }} </span>
        </td>
        <td style="width: 75%" colspan="20" class="p-1">
          <table class="w-100">
            <draggable
              :list="ref(autoban.data.filter(x=>x.model.id===model.id)).value"
              @start="drag=true" @end="drag=false"
              :group="`model${model.id}`"
              tag="tbody" item-key="id"
              @change="onRowReorder"
              handle=".handle"
            >
              <template #item="{element}">
                <tr>
                  <td style="width: 3rem" v-if="type === 'autoban'" class="text-center"> <i class="pi pi-bars handle p-1" style="cursor: move"></i> </td>
                  <td style="width: 15rem"> {{ t(element.type,'type_title') }} </td>
                  <td style="width: 6rem"> {{ t(element.year,'year_number') }} </td>

                  <td style="width: 10rem" v-if="type === 'options'">{{ element.pivotsOptions_count }} </td>
                  <td style="width: 10rem" v-if="type === 'options'"
                      :class="element.reviewed === element.pivotsOptionsRequired.count || 'text-warning'">
                    <span v-tooltip.right="{ value: element.pivotsOptionsRequired.data.map(x=>`${t(x,'option_category_title')} <small class='text-muted'>${x.abbreviation}</small>`).join('<br>'), escape: true}">
                      {{ element.pivotsOptionsRequired.count || '-' }}
                    </span>
                  </td>
                  <td style="width: 7rem" v-if="type === 'options'">
                    <div class="form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :checked="element.reviewed === element.pivotsOptionsRequired.count"
                        @change="emits('AutobanReviewed',$event.target.checked,element)"
                      />
                    </div>
                  </td>
                  <td style="width: 15rem" v-if="type === 'options'"> {{ element.latestOptionUpdate }} </td>

                  <td style="width: 13rem" v-if="type === 'autoban' || type === 'prices'">
                    <InputNumber v-if="type === 'prices'" v-model="element.price.official"
                                 input-class="form-control form-control-sm"
                                 mode="decimal" />
                    <span v-if="type === 'autoban'">{{ t(element.price,'official',null,true) }}</span>
                  </td>
                  <td style="width: 13rem" v-if="type === 'prices'">
                    <InputNumber
                      v-model="element.price.commercial"
                      input-class="form-control form-control-sm"
                      mode="decimal"
                    />
                  </td>
                  <td style="width: 13rem" v-if="type === 'prices'">
                    <InputNumber
                      v-model="element.price.sale"
                      input-class="form-control form-control-sm"
                      mode="decimal"
                    />
                  </td>
                  <td style="width: 3rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <i @click="emits('toggle',$event,element)" class="text-primary fa fa-calculator"></i>
                  </td>
                  <td style="width: 10rem" class="text-center" v-if="type === 'autoban'">
                    <div class="form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        @change="emits('price_list_appearance_market_availability',element)"
                        v-model='element.price_list_appearance'
                      />
                    </div>
                  </td>
                  <td style="width: 3rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <div class="form-switch px-2">
                      <input
                        class="form-check-input m-0"
                        type="checkbox"
                        @change="emits('price_list_appearance_market_availability',element)"
                        v-model='element.market_availability'
                      />
                    </div>
                  </td>
                  <td style="width: 7rem" v-if="type === 'options'">
                    <div class="form-switch">
                      <i class="fa fa-edit text-info mx-1" @click="emits('autobanOptionDialog',element)"></i>
                    </div>
                  </td>
                  <td style="width: 7rem" v-if="type === 'autoban' && ($can(`edit_${route.meta.permissionsLayout}`) || $can(`delete_${route.meta.permissionsLayout}`))">
                    <i class="fa fa-edit text-info mx-1"
                       v-if="$can(`edit_${route.meta.permissionsLayout}`)"
                       @click="emits('autobanDialog',element)"></i>
                    <i class="fa fa-trash text-danger mx-1"
                       v-if="$can(`delete_${route.meta.permissionsLayout}`)"
                       @click="emits('autobanDelete',$event,element)"></i>
                  </td>
                  <td style="width: 7rem; padding: 0 !important;" class="text-center" v-if="type === 'prices'">
                    <button @click.prevent="emits('priceChange',element)" type="submit" class="btn btn-sm btn-primary">Confirm</button>
                  </td>
                </tr>
              </template>
            </draggable>
          </table>
        </td>
      </tr>
    </tbody>-->
    <tfoot>
      <tr>
        <td colspan="20">
          <Paginator
            :rows="+autoban.pagination.per_page"
            :totalRecords="autoban.pagination.total"
            :rowsPerPageOptions="[10,20,30]"
            template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            current-page-report-template="Showing {first} to {last} of {totalRecords}"
            @page="page($event,(filtera || customFilter))"></Paginator>
        </td>
      </tr>
    </tfoot>
  </table>
</template>

<script setup>
import Loading from "../../components/Loading.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Paginator from "primevue/paginator";
import Dropdown from "primevue/dropdown";
import Image from "primevue/image";
import InputNumber from 'primevue/inputnumber';
import draggable from 'vuedraggable'

import {computed, ref, watch} from "vue";
import _debounce from "lodash/debounce";
import {useRoute} from "vue-router";
import {usePricelistStore} from "../../store/Pricelist";
import {useAutobanStore} from "../../store/Autoban.mjs";
import {useToast} from "primevue/usetoast";

const toast = useToast();
const route = useRoute()
const props = defineProps({
  type: String || null,
  autoban: Object,
  filters: Object,
  filtera: {
    type: String,
    default: ''
  }
})
const emits = defineEmits([
  'onRowReorder',
  'page',
  'autobanDialog',
  'autobanDelete',
  'price_list_appearance_market_availability',
  'autobanOptionDialog',
  'toggle',
  'priceChange',
  "sort",
  "AutobanReviewed"
])
watch(
  ()=>props.filtera,
  _debounce((val)=>{
    emits('page', {},val)
  },400)
)
const AutobanStore = useAutobanStore()
const filtersCol = ref({
  'brand_title': {value: null},
  'model_title': {value: null},
})
const pricelistStore = usePricelistStore()
const pricelistBrands = computed(()=>pricelistStore.pricelistBrands)
const pricelistModels = computed(()=>pricelistStore.pricelistModels)
pricelistStore.initPricelistBrands()
const customFilter = ref("")
const clear = (e) => {
  if ( e === 'brand_title' && filtersCol.value[e].value ) {
    pricelistStore.initPricelistModels(filtersCol.value[e].value)
    AutobanStore.initAutobans(
      {},
      (filtersCol.value[e].value ? filtersCol.value[e].value[e] : ''),
      'same',
      e
    )
    customFilter.value = (filtersCol.value[e].value ? filtersCol.value[e].value[e] : '');
  }
  else if ( e === 'model_title' ) {
    console.log(filtersCol.value.brand_title.value.brand_title+' '+(filtersCol.value[e].value ? filtersCol.value[e].value[e] : ''))
    AutobanStore.initAutobans(
      {},
      filtersCol.value.brand_title.value
        ? filtersCol.value.brand_title.value.brand_title+' '+(filtersCol.value[e].value ? filtersCol.value[e].value[e] : '')
        : '',
      'like',
      e
    )
    customFilter.value = filtersCol.value.brand_title.value
      ? filtersCol.value.brand_title.value.brand_title+' '+(filtersCol.value[e].value ? filtersCol.value[e].value[e] : '')
      : ''
  }
  else {
    AutobanStore.initAutobans()
  }
}
const meta = ref({})
const filtera = ref("")

const array_move = (arr, old_index, new_index) => {
  arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
  return arr;
}
const OrderedArr = ref(null)
const onRowReorder = async (event) => {
  const autobansFiltered = (OrderedArr.value || AutobanStore.autobans.data.filter(x => +x.model.id === +event.moved.element.model.id));
  let newArray = array_move(autobansFiltered,event.moved.oldIndex,event.moved.newIndex)
  let order = 0;
  if ( !newArray.map(x=>x.order).includes(0) ) {
    order = Math.min.apply( Math, newArray.map(x=>x.order) );
  }
  newArray = newArray.map(x=>{
    x.order = order
    order++
    return x;
  })
  OrderedArr.value = newArray
  await AutobanStore.reOrder(newArray)
  toast.add({
    closable: false,
    severity: "success",
    summary: "autoban",
    detail: "Ordered",
    life: 3000
  })
}
const page = (e,f) => {
  emits('page',e,f,'','',dataSortBig.value,sortDirBig.value)
  meta.value = e;
}
const drag = ref(false)

const dataSortBig = ref("");
const sortDirBig = ref("");
const trSorting = (e) => {
  const target = e.target;
  const dataSort = target.getAttribute('data-sort')
  if ( dataSort ) {
    if ( !target.classList.contains('sorting_desc') && !target.classList.contains('sorting_asc') ) {
      $(e.currentTarget).find('th').removeClass('sorting_desc sorting_asc')
      $(target).addClass('sorting_desc')
    }
    $(target).toggleClass('sorting_asc sorting_desc')
    const sortDir = target.classList[1].replace(/sorting_/g,'')
    dataSortBig.value = dataSort
    sortDirBig.value = sortDir
    emits('sort',meta.value,(filtera.value || customFilter.value),dataSort,sortDir)
  }
}
const findLastIndex = (arr,v,indexStart)=>{
  let index = indexStart;
  for (let i = indexStart+1; i < arr.length+1; i++) {
    const prev = arr[(i ? i-1 : i)]
    const val = arr[i]
    if ( v === prev ) {
      index = i
    } else {
      break
    }
  }
  return index
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
