panel.plugin("cookbook/block-factory", {
    blocks: {
      awesomeblock: `
        <div @click="open">
          {{ content.text }}
        </div>
      `,
     box: {
      computed: {
        textField() {
          return this.field("text");
        }
      },
      template: `
        <div :class="'k-block-type-box box-' + content.boxtype">
          <k-writer-input
            class="label"
            ref="textbox"
            :marks="textField.marks"
            :value="content.text"
            :placeholder="textField.placeholder || 'Enter some stuff…'"
            @input="update({ text: $event })"
          />
          <k-icon
            v-if="content.type !== 'neutral'"
            class="k-block-type-box-icon"
            :type="content.boxtype"
          />
        </div>
      `
  }, 
  accordion: {
      computed: {
        summaryField() {
          return this.field("summary");
        },
        detailsField() {
          return this.field("details");
        }
      },
      template: `
        <div @dblclick="open">
          <details>
            <summary>
              <k-writer-input
                ref="summary"
                :inline="true"
                marks="false"
                :placeholder="summaryField.placeholder || 'Add a summary…'"
                :value="content.summary"
                @input="update({ summary: $event })"
              />
            </summary>
            <k-writer-input
                ref="details"
                :inline="detailsField.inline || false"
                :marks="detailsField.marks"
                :value="content.details"
                :placeholder="detailsField.placeholder || 'Add some details'"
                @input="update({ details: $event })"
              />
          </details>
        </div>
      `
    },
    faq: {
        computed: {
          items() {
            return this.content.faq || {};
          },
          headingField() {
            return this.field("heading");
          },
                  faqQuestionField() {
            return this.field('faq').fields.question;
          },
                  faqAnswerField() {
            return this.field('faq').fields.answer;
          }
        },
        methods: {
          updateItem(content, index, fieldName, value) {
            content.faq[index][fieldName] = value;
            this.$emit("update", {
                ...this.content,
                ...content
              });
          }
        },
        template: `
          <div>
            <h2 class="k-block-type-faq-heading">
              <k-writer-input
                ref="heading"
                :inline="headingField.inline"
                :marks="headingField.marks"
                :placeholder="headingField.placeholder || 'Add a heading'"
                :value="content.heading"
                @input="update({ heading: $event })"
              />
            </h2>
            <div v-if="items.length">
              <details v-for="(item, index) in items" :key="index">
                <summary>
                  <k-writer-input
                    ref="question"
                    :inline="true"
                    :marks="faqQuestionField.marks"
                    :value="item.question"
                    @input="updateItem(content, index, 'question', $event)"
                  />
                </summary>
                <k-writer-input
                  class="label"
                  ref="answer"
                  :marks="faqAnswerField.marks"
                  :value="item.answer"
                  @input="updateItem(content, index, 'answer', $event)"
                />
              </details>
            </div>
            <div v-else>No questions yet</div>
          </div>
        `
      },
    faq2: {
      computed: {
        items() {
          return this.content.faq || {};
        },
        headingField() {
          return this.field("heading") || '';
        }
      },
      methods: {
        updateItem(content, index, name, value) {
          content.faq[index].content[name]= value;
          this.$emit("update", {
              ...this.content,
              ...content
            });
        }
      },
      template: `
        <div @dblclick="open">
          <h2 class="k-block-type-faq-heading">
            <k-writer-input
              ref="heading"
              :inline="headingField.inline"
              :marks="headingField.marks"
              :placeholder="headingField.placeholder || 'Add a heading'"
              :value="content.heading"
              @input="update({ heading: $event })"
            />
          </h2>
          <div v-if="content.faq.length">
            <details
              class="k-block-type-faq-item"
              v-for="(item, index) in items"
              :key="index"
            >
            <summary>
              <k-writer-input
                ref="summary"
                :inline="true"
                :marks="false"
                :value="item.content.summary"
                @input="updateItem(content, index, 'summary', $event)"
              />
            </summary>
            <div>
              <k-writer-input
                ref="details"
                :marks="true"
                :value="item.content.details"
                @input="updateItem(content, index, 'details', $event)"
            />
            </div>
            </details>
          </div>
          <div v-else>No items yet</div>
        </div>
      `
    },
    card: {
      data() {
        return {
          text: "No text value"
        };
      },
      computed: {
        cardType() {
          return this.content.cardtype;
        },
        heading() {
          return (this.cardType === 'manual') ? this.content.heading : this.page.text;
        },
        image() {
          if (this.cardType === 'manual') {
            return this.content.image[0] || {};
          } else {
            return this.page.image || {}
          }
        },
        pageId() {
          return this.page ? this.page.id : '';
        },
        page() {
            return this.content.page[0] || {};
        },
      },
      watch: {
        "cardType": {
          handler (value) {
           if (value === 'page' && this.pageId) {
            this.$api.get('pages/' + this.pageId.replaceAll('/', '+')).then(page => {
              this.text = ""
            });
           } else if (value === 'manual') {
             this.text = this.content.text || this.text;
           }

          },
          immediate: true
        },
        "page": {
          handler (value) {
           if (this.cardType === 'page' && this.pageId) {
            this.$api.get('pages/' + this.pageId.replaceAll('/', '+')).then(page => {
              this.text = ""
            });
           } else if (value === 'manual') {
             this.text = this.content.text || this.text;
           }
          },
          immediate: true
        }
      },
      template: `
        <div @dblclick="open">
          <k-aspect-ratio
            class="k-block-type-card-image"
            cover="true"
            ratio="1/1"
          >
            <img
              v-if="image.url"
              :src="image.url"
              alt=""
            >
          </k-aspect-ratio>
          <h2 class="k-block-type-card-heading">{{ heading }}</h2>
         
        </div>
      `
    },
    }
  });