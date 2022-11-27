<template>
  <PageLayout>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <Loading v-if="pricelistBrands.loading" />
            <DataView v-else :value="pricelistBrands.data" layout="grid">
              <template #grid="val">
                <div class="col-4 col-md-2 my-4 text-center">
                  <Image @click="toggle($event,val.data)"
                         class="pointer" imageStyle="width: 200px"
                         :src="val.data.brand_image" />
                  <span class="d-block mt-2 font-weight-bold">{{ t(val.data,'brand_title') }}</span>
                </div>
              </template>
            </DataView>
          </div>
        </div>
      </div>
    </div>
    <Dialog
      modal dismissable-mask
      append-to=".app-content"
      class="modal-content" style="width: 400px !important;border-radius: 15px"
      content-class="modal-body" position="left"
      v-model:visible="leftDialog">
      <Loading class="d-flex align-items-center" v-if="pricelistModels.loading" />
      <div v-else class="card-body model-body pt-0 px-0">
        <div class="card-header pt-0 pb-4" style="position: sticky; top: 0; z-index: 9;">
          <Image imageStyle="width: 60px" :src="pricelistModels.data.brand_image" />
          <h4 class="mx-2 my-0">{{ t(pricelistModels.data,'brand_title') }}</h4>
        </div>
        <div v-for="model in pricelistModels.data.models" :key="model.id"
             class="py-3 border-bottom position-relative car"
             :class="[pricelistDetails.data.id !== model.id || 'bg-primary-transparent active']">
          <div @click="getDetails(model)" class="row justify-content-center mx-3" style="cursor: pointer">
            <div class="col-7 text-center">
              <Image width="150" :src="model.model_image" />
              <span class="d-block mt-2 font-weight-bold">{{ t(model,'model_title') }}</span>
            </div>
            <div class="col-5 text-start d-flex align-items-center">
              <div>
                <div class="m-1">
                  <i class="fa fa-calendar fs-18"></i>
                  {{ model.autobans.map(x=>x.year.year_number).filter((a,b,c)=>c.indexOf(a)===b).join(" - ") }}
                </div>
                <div class="m-1">
                  <small class="text-muted">Starts from</small>
                  <br> {{ t(model.autobans.map(x=>x.price).reduce((a,b)=>(a.official< b.official?a:b)),'official',null,1) }}
                </div>
              </div>
            </div>
          </div>
          <i class="fa fa-angle-right position-absolute"></i>
        </div>
      </div>
    </Dialog>
    <Dialog
      :show-header="false"
      append-to=".app-content"
      class="modal-content leftDialogCustom"
      style="width: 500px !important;border-radius: 15px"
      content-class="modal-body position-relative pt-0" position="left"
      v-model:visible="leftDetailedDialogComputed">
      <Loading v-if="pricelistDetails.loading && Array.isArray(pricelistDetails.data)" />
      <div v-if="!Array.isArray(pricelistDetails.data)" class="card-body position-relative">
        <Loading class="absolute-loading my-0" v-if="pricelistDetails.loading" />
        <div class="h-100">
          <div class="mb-5">
            <span class="h4 pb-1 border-bottom">Gallery:</span>
            <infinite-slide-bar :duration="`${pricelistDetails.data.gallery.length*2}s`" >
              <div class="lightgallery row flex-nowrap overflow-hidden mt-3 ms-2">
                <a v-for="(img,index) of pricelistDetails.data.gallery"
                   :data-src="img.image"
                   :key="img.id"
                   class="item col d-flex align-items-center text-center pointer p-0 mx-3">
                  <img
                    class="img-responsive rounded"
                    style="width: 100px"
                    :src="img.image"
                    :alt="`Thumb-${index+2}`">
                </a>
              </div>
            </infinite-slide-bar>
          </div>
          <span class="h4 pb-1 border-bottom">Generations:</span>
          <template v-for="year in pricelistDetails.data.autobans.map(x=>x.year.year_number).filter((a,b,c)=>c.indexOf(a)===b).sort((a,b)=>a-b)" :key="year">
            <div v-if="pricelistDetails.data.autobans.map(x=>x.year.year_number).filter((a,b,c)=>c.indexOf(a)===b).length > 1"
                 class="pb-1 border-bottom fs-15 mt-3" style="width: fit-content">
              <i class="fa fa-calendar"></i>
              {{ year }}
            </div>
            <table class="mb-3 p-0 pointer w-100">
              <tr class="border-bottom" :class="[autoban!==autobanSelected || 'bg-primary-transparent']"
                  v-for="(autoban,index) in pricelistDetails.data.autobans.filter(x=>+x.year.year_number === +year)" :key="autoban.id">
                <td class="px-1" @click="autobanSelected = autoban">
                  <div>
                    <span class="rounded-circle d-inline-block" :class="[autoban.market_availability ? 'bg-success' : 'bg-danger']" style="height: 10px;width: 10px;"></span>
                    {{ t(autoban.type,'type_title') }}
                  </div>
                </td>
                <td class="px-1" @click="autobanSelected = autoban">
                  <div> {{ t(autoban.price,'official',null,true) }} </div>
                </td>
                <td class="px-1">
                  <div class="text-start">
                    <img @click="autobansUpdate(autoban)" width="30" v-if="!autobans.map(x=>+x.id).includes(+autoban.id)" :src="`${baseURL}/svg/carComparePlus.svg`" />
                    <img @click="autobansUpdate(autoban)" width="30" v-else :src="`${baseURL}/svg/carCompareMinus.svg`" />
                    <i v-if="index"
                       class="mdi mdi-vector-difference ms-3 me-1 pointer"
                       @click="autobanSelected = autoban;autobanOptionDiffDialog(autoban,pricelistDetails.data.autobans.filter(x=>+x.year.year_number === +year)[index-1])"></i>
                  </div>
                </td>
              </tr>
            </table>
          </template>
          <Divider />
          <div v-if="autobanSelected" class="mx-auto">
            <div class="mb-3">
              <span class="h4 pb-1 border-bottom">Info & Spics:</span>
              <table class="w-100 my-3">
                <tr>
                  <td width="25%" class="text-center py-2">
                    <img width="45" :src="`${baseURL}/svg/gearShift.svg`" alt="">
                    <br> {{ basicOption(28) }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <i class="mdi mdi-engine-outline fs-20 h-0" style="color: #666a8e !important;margin-top: -5px"></i>
                    <br> {{ basicOption(56) }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <i class="la la-horse-head fs-20" style="color: #666a8e !important;"></i>
                    <br> {{ basicOption(55) }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <img width="24" :src="`${baseURL}/svg/turbo.svg`" alt="">
                    <br> {{ basicOption(22) }}
                  </td>
                </tr>
                <tr>
                  <td width="25%" class="text-center py-2">
                    <img width="24" :src="`${baseURL}/svg/warranty.svg`" alt="">
                    <br> {{ basicOption(29) }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <i class="fa fa-calendar" style="color: #666a8e !important;"></i>
                    <br> {{ t(autobanSelected.year,'year_number') }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <i class="fa fa-car" style="color: #666a8e !important;"></i>
                    <br> {{ basicOption(24) }}
                  </td>
                  <td width="25%" class="text-center py-2">
                    <i class="fa fa-globe" style="color: #666a8e !important;"></i>
                    <br> {{ basicOption(25) }}
                  </td>
                </tr>
              </table>
              <div class="text-center">
                <button @click="autobanOptionDialog(autobanSelected)" class="btn btn-sm btn-outline-primary">
                  More +
                </button>
              </div>
            </div>
            <Divider />
            <div class="mb-3">
              <span class="h4 pb-1 border-bottom">Selling Price:</span>
              <div class="my-2">
                <div>
                  <small class="text-muted">Updated:</small>
                  {{ t(autobanSelected.price,'updated_at') }}
                </div>
                <div>
                  <small class="text-muted">Official:</small>
                  {{ t(autobanSelected.price,'official',null,true) }}
                </div>
                <div>
                  <small class="text-muted">Cash:</small>
                  {{ t(autobanSelected.price,'sale',null,true) }}
                </div>
                <div class="d-flex align-items-center">
                  <small class="text-muted">Different:</small>
                  <span class="ms-1" v-if="autobanSelected.price.official - autobanSelected.price.sale > 0">+</span>
                  <span class="ms-1" v-else>-</span>
                  {{ t(Math.abs(autobanSelected.price.official - autobanSelected.price.sale),'self',null,true) }}
                </div>
              </div>
            </div>
            <Divider />
            <div class="mb-3">
              <span class="h4 pb-1 border-bottom">Other options:</span>
              <div class="my-2">
                <div class="text-center">
                  <div v-if="autobanSelected.range.down.concat(autobanSelected.range.up).length" class="py-3 border-bottom position-relative">
                    <div @click="openOtherOptionDialog" class="row justify-content-center mx-3" style="cursor: pointer">
                      <div class="col text-center">
                        <Image width="150" :src="(!autobanSelected.range.down.concat(autobanSelected.range.up)[0] || autobanSelected.range.down.concat(autobanSelected.range.up)[0].model.model_image)" />
                        <span class="d-block mt-2 font-weight-bold">{{ t(!autobanSelected.range.down.concat(autobanSelected.range.up)[0] || autobanSelected.range.down.concat(autobanSelected.range.up)[0].model,'model_title') }}</span>
                      </div>
                      <div class="col text-start d-flex align-items-center">
                        <div>
                          <div>
                            {{ autobanSelected.range.down.concat(autobanSelected.range.up).length }}
                            Choices 4 U
                          </div>
                          <div>
                            <small class="text-muted">Starts from</small>
                            {{ t(autobanSelected.range.down.concat(autobanSelected.range.up).map(x=>x.price).reduce((a,b)=>(a.official< b.official?a:b)),'official',null,1) }}
                          </div>
                          <div>
                            <small class="text-muted">To</small>
                            {{ t(autobanSelected.range.down.concat(autobanSelected.range.up).map(x=>x.price).reduce((a,b)=>(a.official>b.official?a:b)),'official',null,1) }}
                          </div>
                          <div>
                            <small class="text-muted">Based On:</small>
                            <br>
                            <div class="w-100">
                              <div class="text-center d-inline-block py-2">
                                <i class="fa fa-percent" style="color: #666a8e !important;"></i>
                                <br> 10
                              </div>
                              <div class="text-center d-inline-block py-2">
                                <img width="45" :src="`${baseURL}/svg/gearShift.svg`" alt="">
                                <br> {{ basicOption(28) }}
                              </div>
                              <div class="text-center d-inline-block py-2">
                                <i class="fa fa-car" style="color: #666a8e !important;"></i>
                                <br> {{ basicOption(24) }}
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                    <i class="fa fa-angle-right position-absolute"></i>
                  </div>
                  <div v-else>
                    There is No Other Options
                  </div>
                </div>
              </div>
            </div>
            <Divider />
          </div>
        </div>
      </div>
    </Dialog>


    <Dialog
      append-to=".app-content"
      class="modal-content leftDialogOptionandDiff"
      content-class="modal-body" position="right"
      v-model:visible="rightOptionDialogComputed">
      <div class="card-body">
        <ComparisonTable
          type="diff" :option-tree="optionTree"
          :autobans-options="{loading: false, data: [autobanSelected]}"
        />
      </div>
    </Dialog>
    <Dialog
      append-to=".app-content"
      class="modal-content leftDialogOptionandDiff"
      content-class="modal-body" position="right"
      v-model:visible="rightOptionDiffDialogComputed">
      <div class="card-body">
        <ComparisonTable
          type="diff"
          :autobans-options="{loading: false, data: [currentOpt,prevOpt]}" :option-tree="optionTree"
        />
      </div>
    </Dialog>
    <Dialog
      append-to=".app-content"
      class="modal-content leftOtherOptions" style="width: 400px !important;border-radius: 15px"
      content-class="modal-body" position="left"
      v-model:visible="rightOthersDialogComputed">
      <div class="card-body pt-0 px-0">
        <div class="card-header pt-0 pb-4" style="position: sticky; top: 0; z-index: 9;">
          <h4 class="mx-2 my-0">Other Options</h4>
        </div>
        <MoreAutoban
          :array="autobanSelected.range.down.concat(autobanSelected.range.up)"
          @getNewDetails="getNewDetails"
        />
      </div>
    </Dialog>

    <div v-if="leftDetailedDialogComputed" class="background"></div>
  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from "primevue/dataviewlayoutoptions"
import Image from "primevue/image";
import OverlayPanel from 'primevue/overlaypanel';
import Loading from "../../components/Loading.vue";
import Dialog from "primevue/dialog";
import ComparisonTable from "../../components/Option/ComparisonTable.vue";
import MoreAutoban from "../../components/Autoban/MoreAutoban.vue";
import Divider from 'primevue/divider';
import Galleria from 'primevue/galleria';
import lightgallery from 'lightgallery/vue';
import lgThumbnail from 'lightgallery/plugins/thumbnail';
import lgZoom from 'lightgallery/plugins/zoom';
import styles from 'lightgallery/scss/lightgallery.scss';
import InfiniteSlideBar from 'vue3-infinite-slide-bar';

const plugins = ref([lgThumbnail, lgZoom]);

import {usePricelistStore} from "../../store/Pricelist";
import {computed, inject, ref} from "vue";
import {useAutobanOptionsStore} from "../../store/AutobanOptions";
import {useComparisonStore} from "../../store/AutobanOptionComparison";
import {useOptionStore} from "../../store/Options";

const baseURL = import.meta.env.VITE_API_BASE_URL
const t = inject("t")

/* Stores */
const pricelistStore = usePricelistStore()
const AutobanOptionsStore = useAutobanOptionsStore();
const OptionStore = useOptionStore();
const ComparisonStore = useComparisonStore()

/* Stores Data */
const pricelistBrands = computed(()=>pricelistStore.pricelistBrands)
const pricelistModels = computed(()=>pricelistStore.pricelistModels)
const autobans = computed(()=>ComparisonStore.autobans)
const pricelistDetails = computed(()=>pricelistStore.pricelistDetails)

/* Stores Funcs */
pricelistStore.initPricelistBrands()

const leftDialog = ref(false);
const toggle = async (event,data) => {
  leftDetailedDialog.value = false
  autobanOptionDialogShow.value = false
  pricelistStore.pricelistDetails.data = []
  leftDialog.value = true
  await pricelistStore.initPricelistModels(data)
/*
  $('.model-body .car').slideUp(500)
  $('.model-body').mouseover(()=>{
    $('.model-body .car').not('.model-body .car.active').slideDown(500)
  })
  $('.model-body').mouseleave(()=>{
    $('.model-body .car').not('.model-body .car.active').slideUp(500)
  })
*/
};

const autobanOptionDialogShow = ref(false);
const autobanOptionDiffDialogShow = ref(false);
const rightOthersDialogShow = ref(false);

const getNewDetails = async (autoban) => {
  rightOthersDialogShow.value = false
  pricelistStore.initPricelistModels(autoban.model.brand)
  pricelistStore.pricelistDetails.data = []
  getDetails(autoban.model,autoban)
};

const autobansUpdate = (autoban)=>{
  const autobanIndex = ComparisonStore.autobans.findIndex(x=>+x.id === +autoban.id)
  if ( autobanIndex === -1 ) {
    ComparisonStore.autobans.push(autoban)
  } else {
    ComparisonStore.autobans = [...ComparisonStore.autobans.slice(0,autobanIndex), ...ComparisonStore.autobans.slice(autobanIndex+1)]
  }
}
const leftDetailedDialog = ref(false);
const getDetails = async (model, newAutobanSelect = null)=>{
  autobanOptionDiffDialogShow.value = false
  autobanOptionDialogShow.value = false
  rightOthersDialogShow.value = false
  leftDetailedDialog.value = true
  await pricelistStore.initPricelistDetails(model)
  if ( newAutobanSelect ) {
    const indexAutoban = pricelistDetails.value.data.autobans.findIndex(x => +x.id === +newAutobanSelect.id)
    autobanSelected.value = pricelistDetails.value.data.autobans[indexAutoban]
  } else {
    autobanSelected.value = pricelistDetails.value.data.autobans[0]
  }

  lightGallery(document.querySelector('.lightgallery'));
}

const optionTree = computed(()=>OptionStore.optionTree)
const autobanOptionDialog = async (autoban,diff = false)=>{
  autobanOptionDiffDialogShow.value = false
  rightOthersDialogShow.value = false
  autobanOptionDialogShow.value = true
  OptionStore.initOptionTree()
}

const currentOpt = ref(null)
const prevOpt = ref(null)
const autobanOptionDiffDialog = async (autoban,diff = false)=>{
  autobanOptionDialogShow.value = false
  rightOthersDialogShow.value = false
  autobanOptionDiffDialogShow.value = true
  OptionStore.initOptionTree()
  currentOpt.value = autoban
  prevOpt.value = diff
}

const openOtherOptionDialog = ()=>{
  rightOthersDialogShow.value = true
  autobanOptionDiffDialogShow.value = false
  autobanOptionDialogShow.value = false
}

const leftDetailedDialogComputed = computed(()=>{
  return (leftDetailedDialog.value&&leftDialog.value)
})
const rightOptionDialogComputed = computed({
  get: ()=>(leftDetailedDialog.value&&leftDialog.value&&autobanOptionDialogShow.value),
  set: (val)=>autobanOptionDialogShow.value = val
})
const rightOthersDialogComputed = computed({
  get: ()=>(leftDetailedDialog.value&&leftDialog.value&&rightOthersDialogShow.value),
  set: (val)=>rightOthersDialogShow.value = val
})
const rightOptionDiffDialogComputed = computed({
  get: ()=>(leftDetailedDialog.value&&leftDialog.value&&autobanOptionDiffDialogShow.value),
  set: (val)=>autobanOptionDiffDialogShow.value = val
})

const autobanSelected = ref(null);
const basicOption = (optId)=>{
  let rtn = '-';
  if ( !pricelistDetails.value.loading && autobanSelected.value ) {
    const opt = autobanSelected.value.pivots;
    if ( opt && opt.length && opt.filter(x=>+x.category.id===optId).pop() ) {
      if ( opt.filter(x=>+x.category.id===optId).pop().options.length ) {
        if ( +optId === 28 && localStorage.getItem('locale') === 'en' ) {
          rtn = t(opt.filter(x=>+x.category.id===optId).pop().options[0],'abbreviation')
        } else if ( +optId === 24 ) {
          rtn = (t(opt.filter(x=>+x.category.id===optId).pop().options[0],'abbreviation') || t(opt.filter(x=>+x.category.id===optId).pop().options[0],'option_title'))
        } else {
          rtn = t(opt.filter(x=>+x.category.id===optId).pop().options[0],'option_title')
        }
      } else {
        rtn = t(opt.filter(x=>+x.category.id===optId).pop(),'option')
      }
    }
  }
  return rtn;
}

</script>


<style scoped lang="scss">
:deep(.p-overlaypanel) {
  padding: 150px !important;
}
.fe-corner-up-left {
  font-size: 18px;
  left: 0;
  color: red;
}
:deep(.p-highlight){
  opacity: unset !important;
}
.p-float-label label {
  transition: all 0.2s !important;
  padding: 0 10px;
}
.fa-angle-right {
  right: 10px;
  top: calc(50% - 30px);
  font-size: 50px;
}
.rtl .fa-angle-right {
  right: unset;
  left: 10px;
  top: calc(50% - 30px);
  font-size: 50px;
}
.background {
  background: #00000050;
  position: fixed !important;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1000;
}
</style>
