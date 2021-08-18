<template>
  <messages-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <template #conversations>
      <div class="h-full bg-white">
        <div class="max-w-sm flex flex-col w-full h-full pl-8 pr-8 py-6 -mr-4">
          <div class="flex flex-row items-center">
            <div class="flex flex-row items-center">
              <div class="text-xl font-semibold">Conversations</div>
            </div>
            <div class="ml-auto">
              <secondary-button
                @click.prevent="compose.showModal = true"
                class="ml-4"
              >
                Compose
              </secondary-button>
            </div>
          </div>
          <div class="mt-6">
            <div class="bg-white">
              <nav class="flex flex-col sm:flex-row space-x-8">
                <button
                  @click.prevent="filter = 'all'"
                  :class="{
                    'border-blue-600 text-blue-800': filter === 'all',
                    'text-gray-600 border-transparent': filter !== 'all',
                  }"
                  class="
                    text-sm
                    py-2
                    px-0
                    block
                    hover:text-blue-800
                    focus:outline-none
                    border-b-2
                    font-medium
                  "
                >
                  All Conversations
                </button>
                <button
                  @click.prevent="filter = 'archived'"
                  :class="{
                    'border-blue-600 text-blue-800': filter === 'archived',
                    'text-gray-600 border-transparent': filter !== 'archived',
                  }"
                  class="
                    text-sm
                    py-2
                    px-0
                    block
                    hover:text-blue-800
                    focus:outline-none
                    border-b-2
                    font-medium
                  "
                >
                  Archived
                </button>
              </nav>
            </div>
          </div>
          <div class="mt-8">
            <div class="flex flex-col -mx-4">
              <div
                v-if="conversations_source && conversations_source.data"
                :class="{
                  'border-l-2 border-blue-400 bg-blue-50':
                    active_conversation &&
                    conversation.id === active_conversation.id,
                }"
                @click.prevent="switchConversation(conversation)"
                v-for="conversation in conversations_source.data"
                class="
                  -ml-4
                  -mr-4
                  cursor-pointer
                  hover:bg-gray-100
                  relative
                  flex flex-row
                  items-center
                  py-4
                  px-6
                  border-l-2 border-transparent
                "
              >
                <div
                  class="absolute text-xs text-gray-500 right-0 top-2 mr-4 mt-3"
                >
                  <date-time
                    :relative="true"
                    :date="conversation.updated_at"
                  ></date-time>
                </div>
                <div
                  class="
                    flex
                    items-center
                    justify-center
                    h-10
                    w-10
                    rounded-full
                    bg-pink-500
                    text-pink-300
                    font-bold
                    flex-shrink-0
                  "
                >
                  T
                </div>
                <div class="flex flex-col flex-grow ml-3">
                  <div class="text-sm font-medium">
                    {{
                      conversation.contact
                        ? conversation.contact.friendly_name
                        : conversation.phone_number
                    }}
                  </div>
                  <div class="mt-1 text-xs text-gray-500 truncate w-40">
                    {{ conversation.preview_message }}
                  </div>
                </div>
              </div>

              <p
                v-if="
                  !conversations_source ||
                  !conversations_source.data ||
                  conversations_source.data.length === 0
                "
                class="text-sm text-grey-500 text-center"
              >
                There are no conversations.
              </p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div
      class="flex flex-col h-full sm:w-full"
      style="background-color: #f9f9f9"
    >
      <div class="h-full">
        <div
          class="
            h-full
            max-w-7xl
            mx-auto
            py-10
            sm:px-6
            lg:px-8
            flex
            justify-center
            items-center
          "
        >
          <div class="flex mx-auto my-auto">
            <img class="max-w-sm hidden sm:flex" src="/img/no-chats.png" />
            <div class="px-16 py-20">
              <h1 class="text-5xl font-extrabold mb-4 text-grey-900">Oops!</h1>
              <p>You haven't yet created any chats.</p>

              <div class="mt-12 flex">
                <secondary-button @click.prevent="compose.showModal = true">
                  Compose Message
                </secondary-button>
              </div>
              <div class="mt-4">
                <secondary-button @click.prevent="compose.showModal = true">
                  View Contact
                </secondary-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <dialog-modal
      max-width="xl"
      :closeable="true"
      @close="compose.showModal = false"
      :show="compose.showModal"
    >
      <template #title>Compose Message</template>

      <template #content>
        <form method="post" @submit.prevent="sendMessage" class="pt-3 pb-4">
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="phone_number" value="Phone Number" />
            <jet-input
              id="phone_number"
              type="text"
              class="mt-1 block w-full"
              v-model="compose.form.phone_number"
            />
            <jet-input-error
              :message="compose.form.errors.phone_number"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 sm:col-span-4 mt-3">
            <jet-label for="message" value="Message" />
            <jet-textarea
              id="message"
              type="text"
              class="mt-1 block w-full"
              v-model="compose.form.message"
            />
            <jet-input-error
              :message="compose.form.errors.message"
              class="mt-2"
            />
          </div>
        </form>
      </template>

      <template #footer>
        <secondary-button @click.prevent="compose.showModal = false"
          >Cancel</secondary-button
        >
        <jet-button @click.prevent="sendMessage" class="ml-4">
          Compose
        </jet-button>
      </template>
    </dialog-modal>
  </messages-layout>
</template>

<script>
import MessagesLayout from "@/Layouts/MessagesLayout";
import Welcome from "@/Jetstream/Welcome";
import DateTime from "@/Components/DateTime";
import DialogModal from "@/Jetstream/DialogModal";
import FileUploader from "@/Components/FileUploader";
import { useForm } from "@inertiajs/inertia-vue3";
import Lightbox from "@/Components/Lightbox";
import JetButton from "@/Jetstream/Button";
import SecondaryButton from "@/Jetstream/SecondaryButton";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetTextarea from "@/Components/Textarea";

export default {
  components: {
    Lightbox,
    FileUploader,
    DateTime,
    MessagesLayout,
    Welcome,
    DialogModal,
    JetButton,
    SecondaryButton,
    JetInput,
    JetLabel,
    JetInputError,
    JetTextarea,
  },

  props: ["conversations", "archived_conversations", "active_conversation"],

  setup() {
    const form = useForm({
      message: null,
      attachment: [],
    });

    return { form };
  },

  computed: {
    conversations_source() {
      if (this.filter === "all") {
        return this.conversations;
      } else if (this.filter === "archived") {
        return this.archived_conversations;
      }

      return {
        data: [],
        next_page_url: null,
        prev_page_url: null,
      };
    },
  },

  data() {
    return {
      show_lightbox: null,
      filter: "all",
      compose: {
        showModal: false,
        form: this.$inertia.form({
          contact_id: null,
          phone_number: null,
          message: "",
        }),
      },
    };
  },

  mounted() {
    const $inertia = this.$inertia;

    window.Echo.private(
      `App.Models.Team.${this.$page.props.user.current_team.id}`
    ).listen(".incoming-message", function () {
      $inertia.reload({
        only: ["conversations", "active_conversation_messages"],
      });
    });
  },

  watch: {
    active_conversation: {
      immediate: true,
      handler() {
        this.$nextTick(() => {
          const container = this.$el.querySelector("#chat-window");
          container.scrollTop = container.scrollHeight;
        });
      },
    },
  },

  methods: {
    switchConversation(conversation) {
      // this.active_conversation_id = conversation.id;
      this.$inertia.get(
        this.route("conversations.show", conversation.id),
        {},
        {
          preserveState: false,
          preserveScroll: true,
        }
      );
    },

    assignFiles() {
      this.form.attachment = this.$refs.attachmentUpload.files;
    },

    sendMessage() {
      this.compose.form.post(this.route("messages.compose"), {
        onSuccess: () => {
          this.compose.showModal = false;

          this.compose.contact_id = null;
          this.compose.phone_number = null;
          this.compose.message = "";
        },
      });
    },

    sendMessageInConversation() {
      this.form.post(
        route("conversations.messages.store", this.active_conversation_id),
        {
          onSuccess: () => {
            this.form.message = null;
            this.form.attachment = [];
          },
        }
      );
    },
  },
};
</script>
