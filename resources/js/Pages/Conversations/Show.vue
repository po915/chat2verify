<template>
  <messages-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <template #conversations>
      <div
        class="h-full bg-white w-screen lg:w-1/3 sm:block"
        v-bind:class="{ hidden: navHidden }"
      >
        <div
          class="
            max-w-sm
            flex flex-col
            w-full
            h-full
            pl-8
            pr-8
            py-6
            -mr-4
            overflow-auto
          "
        >
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
              <button
                class="ml-5 -mr-4 sm:block lg:hidden"
                style="
                  background: #f2f3f4;
                  border-radius: 5px;
                  padding: 5px 10px;
                "
                @click="toggle()"
              >
                X
              </button>
            </div>
          </div>
          <div class="mt-6">
            <div class="bg-white">
              <nav class="flex space-x-8">
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
                :class="{
                  'border-l-2 border-blue-400 bg-blue-50':
                    active_conversation &&
                    conversation.id === active_conversation.id,
                }"
                @click.prevent="switchConversation(conversation)"
                v-for="conversation in conversations_source.data"
                :key="conversation.id"
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
                @click="toggle()"
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
                v-if="conversations_source.data.length === 0"
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
      class="flex flex-col h-full w-screen"
      style="background-color: #f9f9f9"
      v-bind:class="{ hidden: !navHidden }"
    >
      <template v-if="conversations_source.data.length > 0">
        <div class="flex flex-row items-center py-4 px-6 bg-white">
          <a
            href="#"
            class="
              flex
              items-center
              justify-center
              bg-gray-100
              hover:bg-gray-200
              text-gray-400
              h-10
              w-10
              md:hidden
              rounded
            "
            @click="toggle()"
          >
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="28"
                height="28"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-menu"
              >
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
              </svg>
            </span>
          </a>
          <div class="flex flex-col ml-3">
            <h4
              @click.prevent="show_update_contact_modal = true"
              v-if="active_conversation"
              class="cursor-pointer font-semibold text-sm"
            >
              {{
                active_conversation.contact
                  ? active_conversation.contact.friendly_name
                  : active_conversation.phone_number
              }}
            </h4>
            <div v-if="active_conversation" class="text-xs text-gray-500">
              Last Message at
              <date-time :date="active_conversation.updated_at"></date-time>
            </div>
          </div>
          <div class="ml-auto">
            <ul class="flex flex-row items-center space-x-2">
              <li>
                <jet-dropdown align="right" width="60">
                  <template #trigger>
                    <a
                      href="#"
                      class="
                        flex
                        items-center
                        justify-center
                        bg-gray-100
                        hover:bg-gray-200
                        text-gray-400
                        h-10
                        w-10
                        rounded-full
                      "
                    >
                      <span>
                        <svg
                          class="w-5 h-5"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                          ></path>
                        </svg>
                      </span>
                    </a>
                  </template>

                  <template #content>
                    <div class="w-60">
                      <!-- Team Settings -->
                      <form
                        method="post"
                        @submit.prevent="
                          forms.archive.delete(
                            route(
                              'conversations.destroy',
                              active_conversation.id
                            )
                          )
                        "
                      >
                        <dropdown-link type="submit" as="button">
                          Archive Conversation
                        </dropdown-link>
                      </form>
                    </div>
                  </template>
                </jet-dropdown>
              </li>

              <li>
                <a
                  @click.prevent="show_offcanvas_menu = true"
                  href="#"
                  class="
                    flex
                    items-center
                    justify-center
                    bg-gray-100
                    hover:bg-gray-200
                    text-gray-400
                    h-10
                    w-10
                    rounded-full
                  "
                >
                  <span>
                    <svg
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="fal"
                      data-icon="sticky-note"
                      class="w-5 h-5"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        fill="currentColor"
                        d="M448 348.106V80c0-26.51-21.49-48-48-48H48C21.49 32 0 53.49 0 80v351.988c0 26.51 21.49 48 48 48h268.118a48 48 0 0 0 33.941-14.059l83.882-83.882A48 48 0 0 0 448 348.106zm-120.569 95.196a15.89 15.89 0 0 1-7.431 4.195v-95.509h95.509a15.88 15.88 0 0 1-4.195 7.431l-83.883 83.883zM416 80v239.988H312c-13.255 0-24 10.745-24 24v104H48c-8.837 0-16-7.163-16-16V80c0-8.837 7.163-16 16-16h352c8.837 0 16 7.163 16 16z"
                      ></path>
                    </svg>
                  </span>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="h-full overflow-hidden px-4">
          <div id="chat-window" class="h-full overflow-y-auto">
            <div
              v-if="
                active_conversation_messages &&
                active_conversation_messages.next_page_url
              "
              class="text-center"
            >
              <inertia-link
                :href="active_conversation_messages.next_page_url"
                type="button"
                class="
                  my-4
                  mx-auto
                  inline-block
                  items-center
                  px-4
                  py-2
                  bg-white
                  border border-gray-300
                  rounded-md
                  font-semibold
                  text-xs text-gray-700
                  uppercase
                  tracking-widest
                  shadow-sm
                  hover:text-gray-500
                  focus:outline-none
                  focus:border-blue-300
                  focus:shadow-outline-blue
                  active:text-gray-800
                  active:bg-gray-50
                  transition
                  ease-in-out
                  duration-150
                  ml-4
                "
              >
                See older messages
              </inertia-link>
            </div>
            <div class="grid grid-cols-12 gap-y-2 pt-6 mb-6">
              <div
                v-if="active_conversation"
                :class="{
                  'col-start-1 col-end-8': message.user_id,
                  'col-start-6 col-end-13': !message.user_id,
                }"
                v-for="message in active_conversation_messages.data
                  .slice()
                  .reverse()"
                :key="message.id"
                class="col-start-1 col-end-8 p-3 rounded-lg"
              >
                <div
                  :class="{
                    'flex items-center justify-start flex-row-reverse':
                      !message.user_id,
                    'flex items-center justify-start': message.user_id,
                  }"
                  class="flex flex-row items-center"
                >
                  <img
                    v-if="message.user && message.user.profile_photo_url"
                    :src="message.user.profile_photo_url"
                    class="
                      flex
                      items-start
                      justify-start
                      self-start
                      h-10
                      w-10
                      rounded-full
                      flex-shrink-0
                    "
                  />
                  <div>
                    <template
                      v-for="attachment in message.attachments"
                      :key="attachment.id"
                    >
                      <img
                        width="384"
                        height="384"
                        @click.prevent="show_lightbox = attachment.full"
                        :class="{
                          'ml-3': message.user_id,
                          'mr-3': !message.user_id,
                        }"
                        class="
                          cursor-pointer
                          max-w-xs
                          relative
                          ml-3
                          text-sm
                          bg-white
                          py-2
                          px-4
                          shadow
                          rounded-xl
                          mb-2
                        "
                        :src="attachment.thumbnail"
                      />

                      <lightbox
                        @close="show_lightbox = false"
                        :show="show_lightbox === attachment.full"
                      >
                        <img :src="attachment.full" />
                      </lightbox>
                    </template>
                    <div
                      :class="{
                        'ml-3': message.user_id,
                        'mr-3': !message.user_id,
                      }"
                      class="
                        relative
                        ml-3
                        text-sm
                        bg-white
                        py-2
                        px-4
                        shadow
                        rounded-xl
                      "
                      style="min-width: 200px"
                    >
                      {{ message.message }}
                    </div>

                    <span
                      class="block px-3 py-2 text-xs text-gray-400 space-x-1"
                    >
                      <span
                        class="inline-block"
                        v-if="message.user && message.user.name"
                        >{{ message.user.name }}</span
                      >
                      <span v-if="message.updated_at" class="inline-block">
                        - <date-time :date="message.updated_at"></date-time>
                      </span>
                    </span>
                  </div>
                </div>
              </div>

              <p
                v-if="
                  conversations_source.data.length === 0 ||
                  !active_conversation ||
                  active_conversation_messages.data.length === 0
                "
                class="
                  text-center
                  block
                  w-full
                  text-sm
                  mx-auto
                  col-start-3
                  lg:col-start-2
                  col-end-12
                "
              >
                There are no messages in this thread.
              </p>
            </div>

            <!-- Go to last page of conversation if there are no results -->
            <div
              v-if="
                active_conversation_messages &&
                active_conversation_messages.data.length === 0 &&
                active_conversation_messages.current_page !==
                  active_conversation_messages.last_page
              "
              class="text-center"
            >
              <inertia-link
                :href="active_conversation_messages.last_page_url"
                type="button"
                class="
                  my-4
                  mx-auto
                  inline-block
                  items-center
                  px-4
                  py-2
                  bg-white
                  border border-gray-300
                  rounded-md
                  font-semibold
                  text-xs text-gray-700
                  uppercase
                  tracking-widest
                  shadow-sm
                  hover:text-gray-500
                  focus:outline-none
                  focus:border-blue-300
                  focus:shadow-outline-blue
                  active:text-gray-800
                  active:bg-gray-50
                  transition
                  ease-in-out
                  duration-150
                  ml-4
                "
              >
                See latest messages
              </inertia-link>
            </div>
            <!-- Recent Messages -->
            <div
              v-else-if="
                active_conversation_messages &&
                active_conversation_messages.prev_page_url
              "
              class="text-center"
            >
              <inertia-link
                :href="active_conversation_messages.prev_page_url"
                type="button"
                class="
                  my-4
                  mx-auto
                  inline-block
                  items-center
                  px-4
                  py-2
                  bg-white
                  border border-gray-300
                  rounded-md
                  font-semibold
                  text-xs text-gray-700
                  uppercase
                  tracking-widest
                  shadow-sm
                  hover:text-gray-500
                  focus:outline-none
                  focus:border-blue-300
                  focus:shadow-outline-blue
                  active:text-gray-800
                  active:bg-gray-50
                  transition
                  ease-in-out
                  duration-150
                  ml-4
                "
              >
                See newer messages
              </inertia-link>
            </div>
          </div>
        </div>

        <message-textarea :conversation="active_conversation" />
      </template>

      <div v-else class="h-full">
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
            <img class="max-w-sm" src="/img/no-chats.png" />
            <div class="px-16 py-20">
              <h1 class="text-5xl font-extrabold mb-4 text-grey-900">Oops!</h1>
              <p>You haven't yet created any chats.</p>

              <div class="mt-12 flex">
                <secondary-button @click.prevent="compose.showModal = true">
                  Compose Message
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

    <dialog-modal
      max-width="xl"
      :closeable="true"
      @close="show_update_contact_modal = false"
      :show="show_update_contact_modal"
    >
      <template #title>Change contact name</template>

      <template #content>
        <form method="post" @submit.prevent="updateContact" class="pt-3 pb-4">
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="friendly_name" value="Contact Name" />
            <jet-input
              id="friendly_name"
              type="text"
              class="mt-1 block w-full"
              v-model="forms.update_contact.friendly_name"
            />
            <jet-input-error
              :message="compose.form.errors.friendly_name"
              class="mt-2"
            />
          </div>
        </form>
      </template>

      <template #footer>
        <secondary-button @click.prevent="show_update_contact_modal = false"
          >Cancel</secondary-button
        >
        <jet-button @click.prevent="updateContact" class="ml-4">
          Save
        </jet-button>
      </template>
    </dialog-modal>

    <off-canvas-menu
      max-width="md"
      :closable="true"
      @close="show_offcanvas_menu = false"
      :show="show_offcanvas_menu"
    >
      <div class="p-8 h-full flex flex-col">
        <h2 class="text-xl flex-shrink-0">
          Notes
          <span class="font-semibold text-lg"
            >({{ active_conversation.notes.length }})</span
          >
        </h2>

        <div class="flex-grow h-full max-h-full overflow-y-auto block mt-4">
          <ul>
            <li v-for="note in active_conversation.notes">
              <article
                tabindex="0"
                class="
                  cursor-pointer
                  border
                  rounded-md
                  p-3
                  bg-white
                  flex
                  text-gray-700
                  mb-2
                  focus:outline-none
                  focus:border-blue-500
                "
              >
                <span class="flex-none pt-1 pr-2">
                  <img
                    class="h-8 w-8 rounded-md"
                    src="https://raw.githubusercontent.com/bluebrown/tailwind-zendesk-clone/master/public/assets/avatar.png"
                  />
                </span>
                <div class="flex-1">
                  <header class="mb-1">
                    <span class="font-bold">{{ note.user.name }}</span> added a
                    note
                  </header>
                  <p class="text-gray-600">
                    {{ note.note }}
                  </p>
                  <footer class="text-gray-500 mt-2 text-sm">
                    <date-time :date="note.created_at"></date-time>
                  </footer>
                </div>
              </article>
            </li>
          </ul>
        </div>
      </div>
    </off-canvas-menu>
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
import OffCanvasMenu from "@/Components/OffCanvasMenu";
import MessageTextarea from "@/Components/MessageTextarea";
import DropdownLink from "@/Jetstream/DropdownLink";
import JetDropdown from "@/Jetstream/Dropdown";

export default {
  components: {
    JetDropdown,
    DropdownLink,
    MessageTextarea,
    OffCanvasMenu,
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

  props: [
    "conversations",
    "archived_conversations",
    "active_conversation",
    "active_conversation_messages",
  ],

  setup() {
    const form = useForm({
      message: null,
      attachment: [],
    });

    return {
      form,
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

  computed: {
    conversations_source() {
      if (this.filter === "all") {
        return this.conversations;
      } else if (this.filter === "archived") {
        return this.archived_conversations;
      }
    },
  },

  data() {
    return {
      show_lightbox: null,
      show_offcanvas_menu: null,
      show_update_contact_modal: false,
      filter: "all",
      forms: {
        archive: this.$inertia.form(),
        update_contact: this.$inertia.form({
          friendly_name: this.active_conversation?.contact?.friendly_name,
        }),
      },
      compose: {
        showModal: false,
        form: this.$inertia.form({
          contact_id: null,
          phone_number: null,
          message: "",
        }),
      },
      navHidden: true,
    };
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
    toggle() {
      this.navHidden = !this.navHidden;
      console.log(this.navHidden);
    },
    switchConversation(conversation) {
      // this.active_conversation_id = conversation.id;
      this.$inertia.get(
        this.route("conversations.show", conversation.id),
        {},
        {
          preserveState: true,
          preserveScroll: true,
        }
      );
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

    updateContact() {
      const friendly_name = this.forms.update_contact.friendly_name;

      this.forms.update_contact.put(
        this.route("contacts.update", this.active_conversation.contact_id),
        {
          onSuccess: () => {
            this.show_update_contact_modal = false;
            this.forms.update_contact.reset();
            this.forms.update_contact.friendly_name = friendly_name;
          },
        }
      );
    },
  },
};
</script>
