<template>
  <v-row class="mt-1">
    <v-col cols="12" sm="12" md="10">
      <v-text-field v-model="search" label="Search here"></v-text-field>
    </v-col>
    <v-col cols="12" sm="12" md="2" class="text-right">
      <v-btn @click="exportUsers">
        Export All Users
      </v-btn>
    </v-col>
  </v-row>

  <v-data-table :headers="headers" :items="users" :sort-by="[{ key: 'name', order: 'asc' }]" :search="search" class="elevation-1">
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
      <v-btn color="primary" @click="getUsers">
        Reset
      </v-btn>
    </template>
  </v-data-table>
</template>
<script>
import { VDataTable } from 'vuetify/labs/VDataTable'
import { mapState, mapGetters, mapActions } from "vuex";

export default {
  components: {
    VDataTable: VDataTable,
  },

  data: () => ({
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

  mounted() {
    if (this.logged_in) {
      this.getUsers();
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
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },

  methods: {
    ...mapActions("user", ["getUsers", "getUsersData", "storeUser", "updateUser", "deleteUser", "exportUsers"]),

    // This will applied when login (token) not required
    // exportUsers() {
    //   var exportUrl = this.baseUrl + 'export/users';
    //   window.open(exportUrl, '_blank');
    // },

    editItem(item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.handleDeleteUser(this.users[this.editedIndex].id);
      this.closeDelete()
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
        this.handleUpdateUser(this.users[this.editedIndex].id);
      } else {
        this.handleAddUser();
      }
      this.close()
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
