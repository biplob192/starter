<template>
  <v-row class="mt-1">
    <v-col cols="12" sm="12" md="2">
      <v-select label="Select record per page." :items="perPage" v-model="tableData.perPage"></v-select>
    </v-col>
    <v-col cols="12" sm="12" md="8">
      <v-text-field v-model="search" label="Search here"></v-text-field>
    </v-col>
    <v-col cols="12" sm="12" md="2" class="text-right">
      <v-btn @click="exportUsers">
        Export All Users
      </v-btn>
    </v-col>
  </v-row>

  <!-- <v-data-table :headers="headers" :items="users" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" class="elevation-1" > -->
  <!-- <v-data-table :headers="headers" :items="users" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" :page.sync="tableData.currentPage" class="elevation-1"> -->
  <!-- <v-data-table :headers="headers" :items="tableData.items" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" v-model:page="tableData.currentPage" :items-per-page="tableData.perPage"
    class="elevation-1"> -->
  <!-- <v-data-table :headers="headers" :items="users" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" v-model:page="tableData.currentPage" :items-per-page="tableData.perPage" :server-items-length="tableData.totalItems"
    class="elevation-1"> -->
  <!-- <v-data-table :headers="headers" :items="users" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" v-model:page="tableData.currentPage" :items-per-page="tableData.perPage"
    :server-items-length="tableData.totalItems" :loading="tableData.loading" :hide-default-footer="true" disable-pagination class="elevation-1"> -->

  <v-data-table-server v-model:page="tableData.currentPage" :search="search" v-model:items-per-page="tableData.perPage" :headers="headers" :items-length="tableData.totalItems" :items="tableData.items"
    :loading="tableData.loading" class="elevation-1" item-value="name" @update:options="fetchData">
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>USER LIST</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn color="primary" dark class="mb-2" v-bind="props">
              New Item
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="6">
                    <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-text-field v-model="editedItem.phone" label="Phone"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-text-field v-model="editedItem.password" label="Password"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancel
              </v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="save">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon size="small" class="me-2" @click="editItem(item.raw)">
        mdi-pencil
      </v-icon>
      <v-icon size="small" @click="deleteItem(item.raw)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="fetchData">
        Reset
      </v-btn>
    </template>

    <!-- Custom pagination (enable this, default pagination will be hide) -->
    <!-- <template v-slot:bottom>
      <v-row class="mt-1">
        <v-col cols="12" sm="12" md="2">
          <v-select label="Select record per page." :items="perPage" v-model="tableData.perPage" @update:modelValue="fetchData"></v-select>
        </v-col>
        <v-col cols="12" sm="12" md="10">
          <div class="text-center pt-2">
            <v-pagination v-model="tableData.currentPage" :length="pageCount"></v-pagination>
          </div>
        </v-col>
      </v-row>
    </template> -->
    <!-- </v-data-table> -->
  </v-data-table-server>
</template>
<script>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import { mapState, mapGetters, mapActions } from "vuex";
import axios from "axios";

export default {
  components: {
    VDataTable: VDataTable,
    VDataTableServer: VDataTableServer,
  },

  data: () => ({
    // Server side pagination
    perPage: ["5", "10", "20", "30", "40", "50", "100", "500"],
    tableData: {
      items: [],
      totalItems: 0,
      currentPage: 1,
      perPage: 10,
      loading: true,
    },

    page: 1,
    length: 5,
    itemsPerPage: 10,

    search: '',
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: 'Name', key: 'name', align: 'start' },
      { title: 'Email', key: 'email' },
      { title: 'Phone', key: 'phone' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: {
      name: '',
      email: '',
      phone: '',
      password: '',
    },
    defaultItem: {
      name: '',
      email: '',
      phone: '',
    },
  }),

  created() {
    // this.fetchData();
  },

  mounted() {
    if (this.logged_in) {
      // this.fetchData();
    }
  },

  computed: {
    // Import states
    ...mapState("user", ["success_message", "users"]),
    ...mapState(["baseUrl"]),

    // Import getters
    ...mapGetters({
      logged_in: "loginStatus",
      user_role: "userRole",
    }),

    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },

    pageCount() {
      return Math.ceil(this.tableData.totalItems / this.tableData.perPage)
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
    name() {
      this.search = String(Date.now())
    },
  },

  methods: {
    ...mapActions("user", ["getUsers", "getUsersData", "storeUser", "updateUser", "deleteUser", "exportUsers"]),

    initialize() {
      let params = new URLSearchParams();
      params.append("page", this.tableData.currentPage);
      params.append("perPage", this.tableData.perPage);
      this.getUsersData(params);
    },

    // This will applied when login (token) not required
    // exportUsers() {
    //   var exportUrl = this.baseUrl + 'export/users';
    //   window.open(exportUrl, '_blank');
    // },

    editItem(item) {
      this.editedIndex = this.tableData.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.tableData.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.handleDeleteUser(this.tableData.items[this.editedIndex].id);
      this.closeDelete()
      this.fetchData();
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save() {
      if (this.editedIndex > -1) {
        this.handleUpdateUser(this.tableData.items[this.editedIndex].id);
      } else {
        this.handleAddUser();
      }
      this.close()
      this.fetchData();
    },

    fetchData: async function () {
      this.tableData.loading = true;
      try {
        // Search only when the key length is enough
        if (this.search != '' && this.search.length <= 2) {
          return false
        }

        // let response = await axios.get('http://127.0.0.1:8000/api/users/get/data', {
        //   headers: {
        //     Accept: "application/json",
        //     Authorization: "Bearer " + localStorage.getItem("access_token"),
        //   },
        //   params: {
        //     page: this.tableData.currentPage,
        //     perPage: this.tableData.perPage,
        //     search: this.search
        //   },
        // });

        let params = new URLSearchParams();
        params.append("perPage", this.tableData.perPage);
        params.append("page", this.tableData.currentPage);
        params.append("search", this.search);

        let info = await this.getUsersData(params);
        let response = JSON.parse(info);


        if (response.status == 200) {
          // console.log(response.data.data)
          this.tableData.totalItems = response.data.data.total;
          this.tableData.items = response.data.data.data;
        }

      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.tableData.loading = false;
      }
    },

    handleAddUser: async function () {
      try {
        let formData = new FormData();
        formData.append("name", this.editedItem.name);
        formData.append("email", this.editedItem.email);
        formData.append("phone", this.editedItem.phone);
        formData.append("password", this.editedItem.password);

        let info = await this.storeUser(formData);
        let response = JSON.parse(info);

        if (response.status == 201) {
          alert(this.success_message);
        }
      } catch (e) {
        alert(e.response.data.message);
      }
    },

    handleUpdateUser: async function (id) {
      try {
        let formData = new FormData();
        formData.append("name", this.editedItem.name);
        formData.append("email", this.editedItem.email);
        formData.append("phone", this.editedItem.phone);
        formData.append("password", this.editedItem.password);
        formData.append("_method", 'PUT');

        let info = await this.updateUser([id, formData]);
        let response = JSON.parse(info);

        if (response.status == 200) {
          alert(this.success_message);
        }
      } catch (e) {
        alert(e.response.data.message);
      }
    },

    handleDeleteUser: async function (id) {
      try {
        let info = await this.deleteUser(id);
        let response = JSON.parse(info);

        if (response.status == 200) {
          alert(this.success_message);
        }
      } catch (e) {
        alert(e.response.data.message);
      }
    },

  },
}
</script>

<style scoped>
.answer {
  font-weight: bold;
  color: orangered;
}

a:hover {
  color: orangered;
  text-decoration: underline;
}

a {
  display: inline-block;
  font-size: 14px;
  text-decoration: none;
  /* padding: 5px;
  margin: 5px; */
  border-left: 1px solid var(--color-border);
}

.post_title {
  font-size: 21px;
  text-decoration: none;
  margin: 5px;
  cursor: pointer;
}

.post_title:hover {
  color: orangered;
  text-decoration: underline;
}
</style>
