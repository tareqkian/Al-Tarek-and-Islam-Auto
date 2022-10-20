<template>
  <PageLayout>
    <div class="main-proifle">
      <div class="row">
        <div class="col-xl-7">
          <div class="box-widget widget-user">
            <div class="widget-user-image d-sm-flex">
              <span class="avatar" style="overflow: hidden">
                <Image :src="user.avatar" preview/>
              </span>

              <div class="ms-sm-4 mt-4">
                <h4 class="pro-user-username mb-3 font-weight-semibold">
                  {{ user.name }}
                  <i class="fa fa-check-circle-o text-success ms-1 fs-14"></i>
                </h4>
                <div class="d-flex mb-2">
                  <span class="feather feather-mail icons"></span>
                  <div class="h6 mb-0 ms-3 mt-1">
                    {{ user.email }}
                  </div>
                </div>
                <div class="d-flex">
                  <span class="fa fa-phone icons"></span>
                  <div class="h6 mb-0 ms-3 mt-1">
                    01#########
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-5 col-lg-7">
          <div class="text-xl-end mt-4 mt-xl-0">
            <button @click="userDialog()" type="button" class="btn btn-primary ms-3">
              <i class="feather feather-edit"></i>
              Edit Profile
            </button>
            <button @click="passwordDialog()" type="button" class="btn btn-primary ms-3">
              <i class="feather feather-edit"></i>
              Change Password
            </button>
          </div>
          <div class="mt-5">
            <div class="main-profile-contact-list row">
              <div class="media col-sm-4 p-0">
                <div class="media-icon bg-primary  me-3 mt-1">
                  <i class="las la-edit fs-20 text-white"></i>
                </div>
                <div class="media-body">
                  <span class="text-muted">
                    Posts
                  </span>
                  <div class="font-weight-semibold fs-25">
                    ###
                  </div>
                </div>
              </div>
              <div class="media col-sm-4 p-0">
                <div class="media-icon bg-success me-3 mt-1">
                  <i class="las la-users fs-20 text-white"></i>
                </div>
                <div class="media-body">
                  <span class="text-muted">Followers</span>
                  <div class="font-weight-semibold fs-25">
                    ###
                  </div>
                </div>
              </div>
              <div class="media col-sm-4 p-0">
                <div class="media-icon bg-orange me-3 mt-1">
                  <i class="las la-wifi fs-20 text-white"></i>
                </div>
                <div class="media-body">
                  <span class="text-muted">Following</span>
                  <div class="font-weight-semibold fs-25">
                    #,###
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="profile-cover">
        <div class="wideget-user-tab">
          <div class="tab-menu-heading p-0">
            <div class="tabs-menu1 px-3">
              <ul class="nav">
                <li v-for="(tab,index) in tabs" :key="index">
                  <a :class="[index || 'active']" :href="`#${tab}`" data-bs-toggle="tab">
                    {{ tab }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="border-0">
          <div class="tab-content">

            <div v-for="(tab,index) in tabs" :key="index" class="tab-pane" :class="[index || 'active']" :id="tab">
              <div class="card">
                <div class="card-body">
                  {{ tab }}
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>


    <handleUser
      v-model="userDialogShow"
      :profile="true"
      :loading="loading"
      :errors="errors"
      :defaultImg="defaultImg"
      :userForm="userForm"
      @handleUserForm="handleUserForm"
    />

    <Dialog
      modal
      dismissableMask
      class="modal-content modal-lg"
      content-class="modal-body"
      :showHeader="false"
      v-model:visible="passwordDialogShow">
      <form @submit.prevent="changePassword">
        <div class="form-floating my-2">
          <input class="form-control"
                 type="password"
                 v-model="newPassword.oldPassword"
                 :class="[!errors.oldPassword || 'is-invalid']"
                 placeholder="Name">
          <label> Old Password </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.oldPassword" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 type="password"
                 v-model="newPassword.newPassword"
                 :class="[!errors.newPassword || 'is-invalid']"
                 placeholder="Name">
          <label> New Password </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.newPassword" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="form-floating my-2">
          <input class="form-control"
                 type="password"
                 v-model="newPassword.newPassword_confirmation"
                 :class="[!errors.newPassword_confirmation || 'is-invalid']"
                 placeholder="Name">
          <label> New Password Confirmation </label>
          <div class="invalid-feedback">
            <ul>
              <li v-for="err in errors.newPassword_confirmation" :key="err"> {{err}} </li>
            </ul>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-center pb-0">
          <button type="submit" class="btn btn-primary" :class="[!loading || 'btn-loading']">Save</button>
          <button type="button" class="btn btn-secondary" @click="passwordDialogShow = !passwordDialogShow">Close</button>
        </div>
      </form>
    </Dialog>

  </PageLayout>
</template>

<script setup>
import PageLayout from "../../components/Layouts/PageLayout.vue";
import handleUser from "/src/components/User/handleUser.vue"
import Dialog from "primevue/dialog";
import Image from 'primevue/image';

import { useToast } from "primevue/usetoast";
import {useAuth} from "../../store/Auth";
import {computed, ref} from "vue";
import {useUsersStore} from "../../store/Users";

const toast = useToast();
const authStore = useAuth();
const user = computed(()=>authStore.USER)

const tabs = ref(["First", "Second", "Third",])

const userDialogShow = ref(false);
const loading = ref(false);
const errors = ref({});
const defaultImg = ref(null)
const userForm = ref({
  name: null,
  email: null,
  avatar: null,
  role_id: null,
  Desktop: 0,
  Mobile: 0
})

const userDialog = ()=>{
  userDialogShow.value = !userDialogShow.value
  errors.value = {}
  defaultImg.value = user.value.avatar
  userForm.value.id = user.value.id
  userForm.value.name = user.value.name
  userForm.value.email = user.value.email
  user.value.settings = ( typeof user.value.settings === 'string' ? JSON.parse(user.value.settings) : user.value.settings)
  userForm.value.Desktop = user.value.settings.devices[0].count
  userForm.value.Mobile = user.value.settings.devices[1].count
  userForm.value.avatar = null
  userForm.value.role_id = user.value.role.id
}
const usersStore = useUsersStore();
const handleUserForm = async ()=>{
  try {
    loading.value = true
    errors.value = {}
    await usersStore.updateUser(userForm.value)
    userDialogShow.value = !userDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "Profile",
      detail: "has been Updated",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    errors.value = e
    loading.value = false
  }
}


const passwordDialogShow = ref(false);
const newPassword = ref({
  userId: user.value.id,
  oldPassword: null,
  newPassword: null,
  newPassword_confirmation: null
})
const passwordDialog = ()=>{
  errors.value = {}
  // userDialogShow.value = !userDialogShow.value
  passwordDialogShow.value = !passwordDialogShow.value
  newPassword.value = {
    userId: user.value.id,
    oldPassword: null,
    newPassword: null,
    newPassword_confirmation: null
  }
}
const changePassword = async()=>{
  try {
    loading.value = true
    await usersStore.changePassword(newPassword.value)
    passwordDialogShow.value = !passwordDialogShow.value
    toast.add({
      closable: false,
      severity: "success",
      summary: "Password",
      detail: "has been Changed",
      life: 3000
    })
    loading.value = false
  } catch (e) {
    loading.value = false
    errors.value = e
  }
}

</script>
