import "../vendor/livewire-ui-spotlight/spotlight.js";

// Fix focus in bootstrap modal
document.addEventListener("focusin", function (e) {
    if (e.target.closest(".spotlight") !== null) {
        e.stopImmediatePropagation();
    }
});

const baseSpotlight = window.LivewireUISpotlight;

delete window.LivewireUISpotlight;

window.spotlight = (data) => {
    return {
        dependencyQueryResults: data.dependencyQueryResults,

        ...baseSpotlight(data),

        _init() {
            const el = this;

            this.$watch("selectedCommand", function (value) {
                if (value === null) {
                    return;
                }

                el.selected = 0;

                el.$refs.input.focus();
            });

            this.$watch("isOpen", function (value) {
                document.documentElement.style.overflow = value ? 'hidden' : '';
                document.documentElement.style.paddingRight = value ? 0 : '';

                if (value === false || el.selectedCommand !== null) {
                    setTimeout(() => el.reset(), 100);

                    return;
                }

                const defaultCommand = el.commands.find(
                    (command) => command.default
                );

                if (!defaultCommand) {
                    return;
                }

                el.go(defaultCommand.id);
            });
        },

        selectUp() {
            this.selected = Math.max(
                this.currentDependency ? -1 : 0,
                this.selected - 1
            );
            this.$nextTick(() => {
                this.$refs.results.children[this.selected + 1].scrollIntoView({
                    block: "nearest",
                });
            });
        },

        selectDown() {
            this.selected = Math.min(
                this.filteredItems().length - 1,
                this.selected + 1
            );
            this.$nextTick(() => {
                this.$refs.results.children[this.selected + 1].scrollIntoView({
                    block: "center",
                });
            });
        },

        filteredItems() {
            if (
                this.searchEngine === "search" &&
                this.input &&
                this.showResultsWithoutInput
            ) {
                return this.dependencySearch
                    .getIndex()
                    .docs.map((item, i) => [{ item: item }, i]);
            }

            const baseFilteredItems =
                baseSpotlight(data).filteredItems.bind(this);

            return baseFilteredItems();
        },

        go(id) {
            if (
                this.currentDependency !== null &&
                this.currentDependency.type === "search" &&
                !id &&
                !(this.selected in this.filteredItems())
            ) {
                return this.reset();
            }

            const baseGo = baseSpotlight(data).go.bind(this);

            return baseGo(id);
        },

        dispose() {
            const items = document.querySelectorAll(".spotlight .item");

            items.forEach((item) => item.remove());

            this.reset();
        },

        reset() {
            this.input = "";
            this.inputPlaceholder = data.placeholder;
            this.searchEngine = "commands";
            this.resolvedDependencies = {};
            this.selectedCommand = null;
            this.currentDependency = null;
            this.selectedCommand = null;
            this.requiredDependencies = [];
            this.dependencySearch.setCollection([]);
            this.$refs.input.focus();
            this.$wire.$set("dependencyQueryResults", []);
        },
    };
};
