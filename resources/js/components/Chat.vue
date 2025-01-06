<template>
   <q-toolbar class="bg-primary glossy text-white">
      <q-toolbar-title>
        Chat Application 
      </q-toolbar-title>  
      <q-btn flat icon="person" dense class="q-mr-md">
        {{logUser.name}}
      </q-btn>
      
      <q-btn
        icon-right="logout"
              label="Logout"
              color="primary"
              @click="logout"
            />
    </q-toolbar>
  <q-page class="chat-page">
    <div class="row " style="width: -webkit-fill-available;">
      <div class="col-3 bg-grey-3 q-pa-md user-list shadow-6">
        <q-list>
          <q-item-label header>User List</q-item-label>
          <q-separator />
          <q-item
            v-for="user in users"
            :key="user.id"
            clickable
            @click="selectUser(user)"
            :active="user.id === selectedUser?.id"
          >
            <q-item-section avatar>
              <q-avatar>
                <q-icon name="person" />
              </q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ user.name }}</q-item-label>
            </q-item-section>
            <q-item-section side>
            <q-badge
              v-if="unreadMessages[user.id]"
              color="red"
              transparent
            >
              {{ unreadMessages[user.id] }}
            </q-badge>
      </q-item-section>
          </q-item>
        </q-list>
      </div>

      <div class="col-9 q-pa-md chat-section">
        <div class="column">
          <div class="row justify-between items-center q-pb-md">
            <q-item-label header v-if="selectedUser">
              Chat with <strong class="text-primary" style="font-size: 19px;">{{ selectedUser.name }}</strong>
            </q-item-label>
            <q-item-label v-else>Please select a user to chat</q-item-label>
          </div>
          
          <div class="scroll chat-messages q-mb-md shadow-1" ref="chatMessages">
            <transition
              appear
              enter-active-class="animated fadeIn"
              leave-active-class="animated fadeOut"
             >
             <div v-if="messages.length > 0">
            <div            
              v-for="message in messages"
              :key="message.id"
              :class="{'self-message': message.from === loggedInUserId}"
              class="q-pb-md"
            >
              <span class="message-text">{{ message.text }}</span>
            </div>
          </div>
            <div v-else class="text-center">
                <span><i>No Chat Found!</i></span>
            </div>
            </transition>
            <q-inner-loading
            :showing="visible"
            label-class="text-teal"
            label-style="font-size: 1.1em"
            />
          </div>
          <div class="row items-center no-wrap">
            <q-input
            style="    width: -webkit-fill-available;"
              v-model="newMessage"
              placeholder="Type your message..."
              outlined
              dense
              class="q-mr-sm"
            />
            <q-btn
              label="Send"
              color="primary"
              push
              style="width: 200px;"
              :disable="!selectedUser || !newMessage "
              @click="sendMessage"
            />
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      loggedInUserId: null,
      users: [], 
      selectedUser: null, 
      messages: [], 
      newMessage: "", 
      logUser:{},
      unreadMessages: {},
      visible:false,
    };
  },
  methods: {
    async scrollToBottom() {
      const chatMessages = this.$refs.chatMessages;
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  },
    async fetchUsers() {
      try {
        const token = localStorage.getItem('authToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await axios.get("/api/users");
        this.users = response.data;
        
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    async fetchMessages() {
      this.visible =true;
      if (!this.selectedUser) return;
      try {
        const token = localStorage.getItem('authToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`; 
        const response = await axios.get(`/api/messages/${this.selectedUser.id}`);
        this.messages = response.data;
        this.visible = false;
        this.$nextTick(() => this.scrollToBottom());
      } catch (error) {
        console.error("Error fetching messages:", error);
      }
    },
    async sendMessage() {
      if (!this.newMessage.trim() || !this.selectedUser) return;
      const messageData = {
        to: this.selectedUser.id,
        from: this.loggedInUserId,
        text: this.newMessage,
      };

      try {
        this.visible = true;
        const token = localStorage.getItem('authToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`; 
        await axios.post("/api/messages", messageData);
        this.messages.push({ ...messageData, id: Date.now() });
        this.newMessage = "";
        this.visible = false;
        this.$nextTick(() => this.scrollToBottom());
        setTimeout(this.fetchMessages, 1000); 
      } catch (error) {
        this.visible = false;
      }
    },
    async selectUser(user) {
      this.selectedUser = user;
      if (this.unreadMessages[user.id]) {
        this.unreadMessages[user.id] = 0;
      }
      this.fetchMessages();
    },
    async logout() {
        await axios.post('/api/logout');
        localStorage.removeItem('authToken');
        delete axios.defaults.headers.common['Authorization'];
        this.$router.push('/login');
     
    },

  },
  mounted() {
      var vm = this;
    this.fetchUsers();
    axios
        .get('/api/user')
        .then((response) => {
          this.logUser = response.data;
          this.loggedInUserId = response.data.id;         
          window.Echo.private(`chat.${this.loggedInUserId}`)
        .listen('MessageSent', (event) => {
          // console.log(event,"event chat.vue",this.selectedUser);
          if (event.to === this.loggedInUserId) {
          if (event.from == this.selectedUser?.id) {            
            this.messages.push(event);
            this.$nextTick(() => this.scrollToBottom());
            this.$q.notify({
              type: "positive",
              position:"top-right",
              message: `New message received!`,
            });
          } else {
            if (!this.unreadMessages[event.from]) {
              this.unreadMessages[event.from] = 0;
            }
            this.unreadMessages[event.from]++;
          }
        }
        });

        })
        .catch(() => {
          this.$router.push('/login');
        });      
      
  },
};
</script>

<style scoped>
.chat-page {
  height: 100vh;
  display: flex;
  flex-direction: row;
}

.user-list {
  border-right: 1px solid #ddd;
}

.chat-section {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 8px;
  border: 1px solid #ddd;
  background: #f8f8f8;
  border-radius: 6px;
}

.message-text {
  padding: 8px 12px;
  border-radius: 8px;
  background: #e0e0e0;
  display: inline-block;
}

.self-message {
  text-align: right;
}

.self-message .message-text {
  background: #1976d2;
  color: white;
}

.scroll {
  max-height: 300px;
  overflow-y: auto;
}
</style>
