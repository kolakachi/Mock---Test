<template>
<div>
    <div class="dump-container">   
        <a class="add-column-btn" :href="url.dumpSQL">Dump SQL</a>
    </div>
    <div class="container">
    

    <div class="column-item" v-for="(column, index) in columns" :key="index">
        <div class="column-header">
            <textarea class="" 
                aria-label="Settings - ongoing" 
                spellcheck="false" dir="auto" 
                maxlength="512"
                @change="storeBoard()"
                v-model="column.name" 
                ></textarea>
                <!-- <div class="delete-btn-container"> -->
                    <button title="Delete"  class="delete-btn" type="button"
                        @click="deleteColumn(index)">
                        <svg width="14" height="14" viewBox="0 0 24 24">
                            <g stroke="#000000" stroke-width="3" fill="none" fill-rule="evenodd" stroke-linecap="round">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </g>
                        </svg>
                    </button>
                <!-- </div> -->
        </div>
        <div class="column-body">
            <draggable class="card-group" :list="column.cards" group="cards" @change="storeBoard()">
                <div class="card-item"  v-for="(element, elementIndex) in column.cards"
                    :key=" element.name +'-'+ elementIndex" @click="editCardDetails(index, elementIndex)">
                   <span class="card-title">{{ element.name }}</span> 
                </div>
            </draggable>
        </div>
        <div class="column-footer">
            <button class="add-btn" @click="addCard(index)">+ Add a card</button>
        </div>
    </div>
    <div class="column-item">
        <div v-if="!userWantsToAddColumn">
            <button class="add-column" @click="setNewColumTitle">+ Add a column</button>
        </div>
        <div v-if="userWantsToAddColumn">
            <input class="column-title" type="text" v-model="columnTitle" placeholder="Enter column title…">
            <button class="add-column-btn" @click="addColumn()">+ Add Column</button>
            <button class="remove-column-btn" @click="cancelNewColumTitle()">Cancel</button>

        </div>
    </div>

    <modal name="add-card-modal" :width="300" :height="330">
        <div class="modal-header">
            <span>Add Card</span>
            
        </div>
        <div class="modal-body">
            <div>
                <label for="">Card name</label>
                <input type="text" v-model="newCard.name">
            </div>
            <div>
                <label for="">Card description</label>
                <textarea name="" id="" rows="10" v-model="newCard.description"></textarea>
            </div>            
        </div>
        <div class="modal-footer">
            <button class="add-column-btn" @click="addNewCard()">Add card</button>
        </div>
    </modal>
    <modal name="edit-card-modal" :width="300" :height="330">
        <div class="modal-header">
            <span>Edit Card</span>
        </div>
        <div class="modal-body">
            <div>
                <label for="">Card name</label>
                <input type="text" v-model="editCard.name">
            </div>
            <div>
                <label for="">Card description</label>
                <textarea name="" id="" rows="10" v-model="editCard.description"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="add-column-btn" @click="updateCard()">Update card</button>
        </div>
    </modal>

    <modal name="delete-column-modal" :width="300" :height="160">
        <div class="modal-header">
            <span>Delete Column</span>
        </div>
        <div class="modal-body">
            <div>
                <p>Are you sure you want to delete this? This action can not be undone.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="delete-column-btn" @click="deleteSelectedColumn()">Delete</button>
            <button class="add-column-btn" @click="hideDeleteCardModal()">Cancel</button>
        </div>
    </modal>
    
</div>
</div>

</template>
<style lang="scss" scoped>
   @import '../../sass/app.scss'
</style>

<script>
import draggable from 'vuedraggable'
import axios from 'axios';

    export default {
        mounted() {
            this.csrfToken = document.querySelector('[name="_token"]').value;
            let columns = document.getElementById("columns").value;
            let boardId = document.getElementById("boardId").value;
            let storeUrl = document.getElementById("store-url").value;
            let dumpSQL = document.getElementById("dump-sql-url").value;
            this.columns = JSON.parse(columns);
            this.boardId = boardId;
            this.url.store = storeUrl;
            this.url.dumpSQL = dumpSQL;
        },
        components: {
            draggable
        },
        data() {
            return {
                userWantsToAddColumn: false,
                boardId: 0,
                csrfToken: '',
                columnTitle: '',
                columns: [
                    {
                        "name" : "list1",
                        "cards" : [
                            { name: "John", description: "Just checking" },
                            { name: "Joao", description: "put 2 and 2 together" },
                            { name: "Jean", description: "lazy 3 wokrer" },
                            { name: "Gerard", description: "for 4th" }
                        ],
                    },{
                        "name" : "list2",
                        "cards": [
                            { name: "Juan", description: "like 5 and six" },
                            { name: "Edgard", description: "number 6" },
                            { name: "Johnson", description: "7 days of the week" }
                        ]
                    }
                
                ],
                columnIndex: -1,
                newCard : {
                    name: '',
                    description: ''
                },
                editCard : {
                    name: '',
                    description: ''
                },
                url : {
                    store : ``,
                    dumpSQL:``
                }
            
            };
        },
        methods: {
            setNewColumTitle(){
                this.userWantsToAddColumn = true;
            },
            cancelNewColumTitle(){
                this.columnTitle = '';
                this.userWantsToAddColumn = false;
            },
            addColumn(){
                this.columns.push({
                    name : this.columnTitle,
                    cards: []
                });
                this.cancelNewColumTitle();
                this.storeBoard();
            },
            addCard(index){
                this.columnIndex = index;
                this.showAddCardModal();

            },
            addNewCard(){
                let columnIndex = this.columnIndex;
                this.columns[columnIndex].cards.push({
                    name: this.newCard.name,
                    description: this.newCard.description
                });

                this.newCard.name = '';
                this.newCard.description = '';
                this.hideAddCardModal();
                this.storeBoard();
                
            },
            showAddCardModal(){
                this.$modal.show('add-card-modal');
            },
            hideAddCardModal() {
                this.$modal.hide('add-card-modal');
            },
            editCardDetails(columnIndex, elementIndex){
                this.editCard = this.columns[columnIndex].cards[elementIndex];
                this.showEditCardModal();
            },
            showEditCardModal(){
                this.$modal.show('edit-card-modal');
            },
            hideEditCardModal() {
                this.$modal.hide('edit-card-modal');
            },
            updateCard(){
                this.hideEditCardModal();
                this.storeBoard();
            },
            deleteColumn(index){
                this.columnIndex = index;
                this.$modal.show('delete-column-modal');
            },
            deleteSelectedColumn(){
                this.columns.splice(this.columnIndex, 1);
                this.hideDeleteCardModal();
                this.storeBoard();

            },
            showDeleteCardModal(){
                this.$modal.show('delete-column-modal');
            },
            hideDeleteCardModal() {
                this.$modal.hide('delete-column-modal');
            },

            storeBoard(){
                const formData = new FormData();
                formData.append('columns', JSON.stringify(this.columns));
                formData.append('board_id', this.boardId);
                formData.append('_token', this.csrfToken);

                axios.post(this.url.store, formData)
                    .then((response) => {
                        this.boardId = response.data.board.id;
                        console.log(response);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }

        }
    }
</script>
