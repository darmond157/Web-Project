<template>
  <CSmartTable
    header
    :items="items"
    :columns="[
      'id',
      'username',
      'email',
      'phone',
      {
        key: 'show_details',
        label: 'Operations',
        _style: { width: '1%' },
        filter: false,
        sorter: false
      }
    ]"
    columnFilter
    itemsPerPageSelect
    :itemsPerPage="8"
    columnSorter
    :loading="loadSpinner"
    pagination
  >
  <template #show_details="{ item }">
    <div style="position:flex;">
      <CButton class="btn btn-outline-primary" @click="
      () => {
        ;(modalVisibilityDelete = true), (this.id = item.id)
      }
    ">Delete
  </CButton>
<CButton class="btn btn-outline-secondary" @click="
      () => {
        ;(modalVisibilityEdit = true),
          (this.id = item.id),
          GetUserInfoFromDB()
      }
    ">Edit</CButton></div>
</template>
  </CSmartTable>

  <CModal
    :visible="modalVisibilityDelete"
    @close="
      () => {
        modalVisibilityDelete = false
      }
    "
  >
    <CModalHeader>
      <CModalTitle>Delete User</CModalTitle>
    </CModalHeader>
    <CModalBody>Are you sure you want to delete user?</CModalBody>
    <CModalFooter>
      <CButton
        color="secondary"
        @click="
          () => {
            modalVisibilityDelete = false
          }
        "
        >Close</CButton
      >
      <CButton color="danger" @click="DeleteUserFromDB">Delete</CButton>
    </CModalFooter>
  </CModal>

  <CModal
    :visible="modalVisibilityEdit"
    @close="
      () => {
        modalVisibilityEdit = false
      }
    "
  >
    <CModalHeader>
      <CModalTitle>Edit User with ID: {{ userInfo.id }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CForm>
        <div class="mb-3">
          <CFormLabel for="emailUpdate">Email:</CFormLabel>
          <CFormInput id="emailUpdate" :value="userInfo.email" />
        </div>
        <div class="mb-3">
          <CFormLabel for="nameUpdate">Username:</CFormLabel>
          <CFormInput id="nameUpdate" :value="userInfo.username" />
        </div>
        <div class="mb-3">
          <CFormLabel for="phoneUpdate">Phone:</CFormLabel>
          <CFormInput id="phoneUpdate" :value="userInfo.phone" />
        </div>
      </CForm>
    </CModalBody>
    <CModalFooter>
      <CButton
        color="secondary"
        @click="
          () => {
            modalVisibilityEdit = false
          }
        "
        >Close</CButton
      >
      <CButton color="primary" @click="EditUserOnDB">Edit</CButton>
    </CModalFooter>
  </CModal>

  <CModal
    :visible="modalVisibilityInfo"
    @close="
      () => {
        modalVisibilityInfo = false
      }
    "
  >
    <CModalHeader>
      <CModalTitle>User Info</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CForm>
        <fieldset disabled>
          <div class="mb-3">
            <CFormLabel for="emailInfo">Email:</CFormLabel>
            <CFormInput id="emailInfo" :value="userInfo.email" />
          </div>
          <div class="mb-3">
            <CFormLabel for="nameInfo">Name:</CFormLabel>
            <CFormInput id="nameInfo" :value="userInfo.name" />
          </div>
          <div class="mb-3">
            <CFormLabel for="phoneInfo">Phone:</CFormLabel>
            <CFormInput id="phoneInfo" :value="userInfo.phone" />
          </div>
        </fieldset>
      </CForm>
    </CModalBody>
    <CModalFooter>
      <CButton
        color="secondary"
        @click="
          () => {
            modalVisibilityInfo = false
          }
        "
        >Close</CButton
      >
    </CModalFooter>
  </CModal>

</template>
<script>
import axios from 'axios'
import { CSmartTable } from '@coreui/vue-pro'

export default {
  name: 'Dashboard',
  components: {
    CSmartTable
  },
  methods: {
    EditUserOnDB () {
      axios({
        method: 'put',
        url: `/api/users/${this.id}`,
        headers: {
          Authorization: 'Bearer ' + localStorage.token
        },
        data: {
          username: document.getElementById('nameUpdate').value,
          phone: document.getElementById('phoneUpdate').value,
          email: document.getElementById('emailUpdate').value
        }
      })
      this.modalVisibilityEdit = false
      setTimeout(function () {
        location.reload()
      }, 500)
    },
    DeleteUserFromDB () {
      axios({
        method: 'delete',
        url: `/api/users/${this.id}`,
        headers: {
          Authorization: 'Bearer ' + localStorage.token
        }
      }).then(val => {
        console.log(val)
      })
      this.modalVisibilityDelete = false
      setTimeout(function () {
        location.reload()
      }, 1000)
    },
    GetUserInfoFromDB () {
      return axios
        .get(`/api/users/${this.id}`, {
          headers: {
            Authorization: 'Bearer ' + localStorage.token
          }
        })
        .then(value => {
          this.userInfo = value.data[0]
        })
    },
    updateActivateSituation () {
      axios({
        method: 'put',
        url: `/api/users/${this.id}`,
        headers: {
          Authorization: 'Bearer ' + localStorage.token
        },

      })
      console.log(this.id)
      setTimeout(function () {
        location.reload()
      }, 500)
    },
    toggleDetails (item) {
      if (this.details.includes(item._id)) {
        this.details = this.details.filter(_item => _item !== item._id)
        return
      }
      this.details.push(item._id)
    }
  },
  data () {
    return {
      items: [],
      userInfo: '',
      id: 0,
      modalVisibilityDelete: false,
      modalVisibilityEdit: false,
      modalVisibilityInfo: false,
      details: [],
      loadSpinner: true
    }
  },
  beforeCreate () {
    axios.interceptors.response.use('', error => {
      if (error.response.status === 401) this.$router.push('Login')
    })
    axios.get(`/sanctum/csrf-cookie`)
  },
  created () {
    axios
      .get('/api/users/', {
        headers: {
          Authorization: 'Bearer ' + localStorage.token
        }
      })
      .then(res => {
        this.items = res.data
        this.loadSpinner = false
      }).catch(()=>{})
  }
}
</script>