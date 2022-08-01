<template>
    <page-layout-vue :pageTitle="$t(`${nameSpace}.title`)">
        <div class="row justify-content-center">
            <div class="col-4 col-md-1 text-center" v-for="lang in $i18n.availableLocales" :key="lang">
                <div class="card">
                    <div class="card-body">
                        {{lang}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <vue-select
                            class="vue-select form-control justify-content-center"
                            v-model="page"
                            :options="routes"
                            searchable
                            clear-on-select
                            close-on-select
                            :placeholder="$t(`${nameSpace}.choosePage`)"
                            :search-placeholder="$t(`${nameSpace}.search`)"
                            :labelBy="opt=>`${opt.parent} \\ ${opt.route}`"
                        ></vue-select>
                    </div>
                    <!-- <div class="col-2">
                        <vue-select
                            class="vue-select form-control justify-content-center"
                            v-model="lang"
                            :options="options"
                            searchable
                            clear-on-select
                            close-on-select
                            placeholder="Choose Language"
                            search-placeholder="Search..."
                            @search:input="handleSearchInput"
                        ></vue-select>
                    </div> -->
                    <div class="col-12">
                        <div v-if="transSelectedLoading" class="spinner4 my-3">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
                        <div v-else-if="!Array.isArray(transSelectedData) && typeof transSelectedData === 'object'" class="table-responsive">

                            <!-- <button class="btn btn-primary" @click="addNewtrans"> +Add </button> -->

                            <form @submit.prevent="handleTrans">
                                <table class="table table-hover card-table table-vcenter text-nowrap">
                                    <tr>
                                        <th v-for="lang in originalLanguages" :key="lang">{{ lang }}</th>
                                    </tr>
                                    <tr>
                                        <td v-for="(lang,lIndex) in originalLanguages" :key="lIndex" class="p-0">
                                            <div class="my-2" v-for="(it,x) in transSelectedData[lang]" :key="x">
                                                <div class="form-floating">
                                                    <input class="form-control" 
                                                           :placeholder="x" 
                                                           v-model="transSelectedData[lang][x]">
                                                    <label class="text-gray">{{x}}</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <button type="submit" class="btn btn-success"> {{$t(`${nameSpace}.save`)}} </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </page-layout-vue>
</template>

<script setup>
import PageLayoutVue from '../../components/Layouts/PageLayout.vue';
import VueSelect from 'vue-next-select'
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import { computed, inject, ref, watch } from 'vue';
const route = useRoute();
let nameSpace = route.meta.pageTitle.substr(0, 1).toLowerCase() + route.meta.pageTitle.substr(1);
const parentComp = route.meta.component.split('/')[route.meta.component.split('/').length - 2];
nameSpace = `${parentComp}.${nameSpace}`;

console.log(nameSpace)
const $i18n = inject("$i18n")
const { dispatch, state } = useStore();
dispatch('Routes/initROUTES')
const routes = computed(()=>state.Routes.routes)
const page = ref({});
const lang = ref('')
const $swal = inject("$swal");

const originalLanguages = $i18n.availableLocales;
const options = ref(originalLanguages.slice())
const visibleOptions = ref(options.value)
const tempOption = ref("")

// const transSelectedData = computed(()=>state.Trans.transSelected.data);
const transSelectedData = computed({
    get(){ return state.Trans.transSelected.data },
    set(val){ state.Trans.transSelected.data = val }
});
const transSelectedLoading = computed(()=>state.Trans.transSelected.loading);
watch(
    page,
    async (val)=>{
        if ( val ) {
            await dispatch("Trans/initTRAN",{...val})
            if ( Array.isArray(transSelectedData.value) && !transSelectedData.value.length ) {
                $swal({
                    title: $i18n.t(`${nameSpace}.noTransTitle`),
                    text: $i18n.t(`${nameSpace}.noTransText`),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: $i18n.t(`${nameSpace}.noTransConfirmButtonText`),
                    cancelButtonColor: '#d33',
                    cancelButtonText: $i18n.t(`${nameSpace}.noTransCancelButtonText`),
                }).then(async (result) => {
                    if ( result.isConfirmed ) {
                        await dispatch("Trans/storeLANG",{...val})
                        dispatch("Trans/initTRAN",{...val})
                    }
                })
            }
        } else {
            transSelected.value.data = []
        }
    }
)

// const addNewtrans = async () => {
//     /* transSelectedData.value = {
//         ...transSelectedData.value,
//         "opps" : {test: "french", test2: "french2"}
//     } */

//     let newData = {};
//     for (const key in transSelectedData.value) {
//         const element = transSelectedData.value[key];
//         let newEl = {};
//         for (const key2 in element) {
//             const element2 = element[key2];
//             newEl[""] = ""
//             newEl[key2] = element2
//         }
//         newData[key] = newEl;
//     }

//     transSelectedData.value = newData;
//     console.log(transSelectedData.value);
// }

const handleTrans = async ()=>{
    await dispatch('Trans/updateTRANS',[
        {...page.value},
        {...transSelectedData.value}
    ])
    $swal({
		position: 'top-end',
		toast: true,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', $swal.stopTimer)
			toast.addEventListener('mouseleave', $swal.resumeTimer)
		},
		icon: 'success',
		title: $i18n.t(`${nameSpace}.successTitle`)
	});
}

</script>

<style scoped>
.form-floating input::placeholder {
	color: transparent !important;
}
</style>