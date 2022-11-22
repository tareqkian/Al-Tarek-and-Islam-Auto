<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <button v-if="$can(`add_${route.meta.permissionsLayout}`)"
                class="btn btn-primary mb-2 me-3"
                @click="autobanDialog()">
          <i class="fe fe-plus"></i>
          Add Autoban
        </button>
        <div class="card">
          <div class="card-body">
            <!--@onRowReorder="onRowReorder"-->
            <autoban-table
              type="autoban"
              :autoban="autoban"
              :filters="filters"
              :filtera="filter"

              @autobanDialog="autobanDialog"
              @autobanDelete="autobanDelete"
              @price_list_appearance_market_availability="price_list_appearance_market_availability"
              @page="AutobanStore.initAutobans"
              @sort="sort"
            />
          </div>
        </div>
      </div>
    </div>

    <Dialog
      modal class="modal-content modal-lg"
      content-class="modal-body"
      v-model:visible="autobanDialogShow">
      <form @submit.prevent="handleAutoban">

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_brand_id"
            :options="autobanBrands.data"
            :loading="autobanBrands.loading"
            filter filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>`${t(opt,'brand_title')}`"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_brand_id ? 'is-invalid' : '']"
            @change="initModel"
            placeholder="Brand" />
          <label> Brand </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_brand_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_model_id"
            :options="autobanModels.data"
            :loading="autobanModels.loading"
            filter filter-placeholder="Search"
            option-value="id" :option-label="opt=>`${t(opt,'model_title')}`"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_model_id ? 'is-invalid' : '']"
            placeholder="Model" />
          <label> Model </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_model_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <MultiSelect
            v-model="selectedAutoban.autoban_type_id"
            :options="autobanTypes.data" :loading="autobanTypes.loading"
            :selectAll="false"
            filter filter-placeholder="Search"
            option-value="id" :option-label="opt=>t(opt,'type_title')"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_type_id ? 'is-invalid' : '']"
            placeholder="Type">
            <template #option="slotProps">
              <div class="ms-2 country-item d-flex justify-content-start align-items-center">
                {{ slotProps.option.type_title }}
              </div>
            </template>
          </MultiSelect>

          <label> Type </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_type_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <Dropdown
            v-model="selectedAutoban.autoban_year_id"
            :options="autobanYears.data"
            :loading="autobanYears.loading"
            filter
            filter-placeholder="Search"
            option-value="id"
            :option-label="opt=>t(opt,'year_number')"
            class="form-control d-flex align-items-stretch"
            :class="[errors.autoban_year_id ? 'is-invalid' : '']"
            placeholder="Year" />
          <label> Year </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.autoban_year_id" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="autobanDialogShow = !autobanDialogShow">Close</button>
        </div>
      </form>
    </Dialog>




  </PageLayout>
</template>
<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import AutobanTable from "../../components/Autoban/AutobanTable.vue"
import Loading from "../../components/Loading.vue";
import BrandDropDown from "../../components/Autoban/BrandDropDown.vue";

import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue/useconfirm";
import {useRoute} from "vue-router";
import {computed, inject, ref, watch} from "vue";
import {useAutobanStore} from "../../store/Autoban";
import {FilterMatchMode} from "primevue/api";

const filters = ref({'global': { value: null, matchMode: FilterMatchMode.CONTAINS }})
const filter = ref(null)
const t = inject("t");
const toast = useToast();
const confirm = useConfirm();
const route = useRoute();
const AutobanStore = useAutobanStore()
const autobanBrands = computed(()=>AutobanStore.autobanBrands)
const autobanModels = computed(()=>AutobanStore.autobanModel)
const autobanTypes = computed(()=>AutobanStore.autobanTypes)
const autobanYears = computed(()=>AutobanStore.autobanYears)
const autoban = computed(()=>AutobanStore.autobans)
AutobanStore.initAutobans()



// const meta = ref({})
// const filtera = ref("")

// const onRowReorder = async (event) => {
//
//   let z = AutobanStore.autobans.data.filter(x=>JSON.stringify(x).toLowerCase().includes((filters.value.global.value || '').toLowerCase()))
//   let order = 0;
//   for (let index = 0; index < z.length; index++) {
//     let x = event.value[index];
//     if (index && x.model.id !== event.value[index-1].model.id ) order = 0
//     const autobanIndex = AutobanStore.autobans.data.findIndex(z => z.id === x.id);
//     x.order = order
//     AutobanStore.autobans.data[autobanIndex] = {...x}
//     order++
//   }
//
//   /*AutobanStore.autobans.data = event.value;*/
//
//
//   await AutobanStore.reOrder(z)
//   await AutobanStore.initAutobans(meta.value,filtera.value)
//
//   toast.add({
//     closable: false,
//     severity: "success",
//     summary: "autoban",
//     detail: "Ordered",
//     life: 3000
//   })
// }

/*
const page = (m,f)=>{
  AutobanStore.initAutobans(m,f)
  meta.value = m;
  filtera.value = f;
}
*/




const errors = ref([])
const loading = ref(false);
const autobanDialogShow = ref(false)
const selectedAutoban = ref({
  id: null,
  autoban_brand_id: null,
  autoban_model_id: null,
  autoban_type_id: [],
  autoban_year_id: null,
  price_list_appearance: null,
  market_availability: null,
})
const autobanDialog = (autoban = {})=>{
  errors.value = []
  autobanDialogShow.value = !autobanDialogShow.value
  AutobanStore.initAutobanBrands()

  if ( autoban.model ) AutobanStore.initAutobanModel(autoban.model.brand.id)

  AutobanStore.initAutobanTypesAll()
  AutobanStore.initAutobanYearsAll()

  console.log(autoban)

  selectedAutoban.value.id = autoban.id || null
  selectedAutoban.value.autoban_brand_id = (autoban.model ? autoban.model.brand.id : null)
  selectedAutoban.value.autoban_model_id = (autoban.model ? autoban.model.id : null)
  selectedAutoban.value.autoban_type_id = (autoban.type ? [autoban.type.id] : [])
  selectedAutoban.value.autoban_year_id = (autoban.year ? autoban.year.id : null)
  selectedAutoban.value.price_list_appearance = (autoban.id ? autoban.price_list_appearance : 1)
  selectedAutoban.value.market_availability = (autoban.id ? autoban.market_availability : 1)
}
const initModel = ()=>{
  console.log(selectedAutoban.value.autoban_brand_id)
  AutobanStore.initAutobanModel(selectedAutoban.value.autoban_brand_id)
}
const handleAutoban = async()=>{
  try {
    errors.value = []
    loading.value = true
    await AutobanStore.handleAutobans(selectedAutoban.value)
    autobanDialogShow.value = !autobanDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "autoban",
      detail: "Success",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}
const price_list_appearance_market_availability = async(autoban)=>{
  try {
    autoban.autoban_model_id = autoban.model.id;
    autoban.autoban_type_id = [autoban.type.id];
    autoban.autoban_year_id = autoban.year.id;
    await AutobanStore.handleAutobans(autoban)
    toast.add({
      closable: false,
      severity: "success",
      summary: "autoban",
      detail: "has been Updated",
      life: 3000
    })
  } catch (e) {
    toast.add({
      closable: false,
      severity: "danger",
      summary: "autoban",
      detail: e,
      life: 3000
    })
  }
}
const autobanDelete = (event,autoban)=>{
  console.log(event)
  console.log(autoban)
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
        await AutobanStore.distroyAutoban(autoban)
        toast.add({
          closable: false,
          severity: "success",
          summary: "autoban",
          detail: "has been Deleted",
          life: 3000
        })
      } catch (e) {
        toast.add({
          closable: false,
          severity: "danger",
          summary: "autoban",
          detail: e,
          life: 3000
        })
      }
    }
  });
}


const sort = (meta,filter,dataSort,sortDir)=>{
  AutobanStore.initAutobans(meta, filter, '', '',dataSort,sortDir)
}

</script>
