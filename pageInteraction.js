//This function helps display a help note for the user
function helpNote(helpSection) {
    var hideVar = document.getElementById(helpSection);
    if (hideVar.style.display === "none") {
        hideVar.style.display = "block";
        document.getElementById("loginBox").style.height = "16em";
    } else {
        hideVar.style.display = "none";
        document.getElementById("loginBox").style.height = "10em";
    }
}

//This function helps minimise a section the user has selected by pressing a "display" button
function hideContent(contentID) {
    var hideVar = document.getElementById(contentID);
    if (hideVar.style.display === "none") {
        hideVar.style.display = "block";
    } else {
        hideVar.style.display = "none";
    }
}

//This function helps minimise a section that is identified by the contentID variable
function minimise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "none";
}

//This function helps maximise a section that is identified by the contentID variable
function maximise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "block";
}

//This function is used to automatically scroll the user back to the top of the page
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  }

//This function opens send new message box and close all the other boxes
function messageAdd(){
    maximise('classAdd');
    minimise('deleteMessage');
    minimise('inbox');
    minimise('sent');
}

//This function opens inbox and closes all the other boxes
function messageInbox(){
    topFunction();
    maximise('inbox');
    minimise('classAdd');
    minimise('sent');
    minimise('deleteMessage');
}

//This function opens send new message box and close all the other boxes
function messageSent(){
    topFunction();
    maximise('sent');
    minimise('classAdd');
    minimise('inbox');
    minimise('deleteMessage');
}

//This function opens the view classes box and close all the other boxes
function viewClasses(){
    topFunction();
    maximise('classViewer');
    minimise('classAdd');
    minimise('addAcount');
    minimise('seachAccount');
}
//This function opens the add class box and close all the other boxes
function addClass(){
    topFunction();
    minimise('classViewer');
    minimise('deleteClass');
    maximise('classAdd');
    minimise('updateClass');
    minimise('addAcount');
    minimise('seachAccount');
}

//This function closes all boxes
function closeOnNo(){
    topFunction();
    minimise('classAdd');
    minimise('deleteClass');
    minimise('updateClass');
    minimise('addAcount');
    minimise('seachAccount');
}

//This function opens the search account box and close all the other boxes
function openSearch(){
    topFunction();
    maximise('seachAccount');
    minimise('classViewer');
    minimise('deleteClass');
    minimise('classAdd');
    minimise('updateClass');
    minimise('addAcount');
}

//This function closes every box
function viewAccountCloser(){
    minimise('accountAddClass');
    minimise('removeclass');
    minimise('updateAssignedClass');
    minimise('accountUpdate');
    minimise('accountDelete');
}

//This function opens the add account box and close all the other boxes
function addAccount(){
    maximise('addAcount');
    minimise('classViewer');
    minimise('deleteClass');
    minimise('classAdd');
    minimise('updateClass');
    minimise('seachAccount');
}

//This function closes all of the boxes and clears the question title and question inputs
function abortMessage(){
    minimise('sent');
    minimise('classAdd');
    minimise('inbox');
    minimise('deleteMessage');

    var blank = "";
    document.getElementsByName("questionTitle")[0].value = blank;
    document.getElementsByName("sendQuestion")[0].value = blank;
}

//This function closes the message histroy box
function abortMessageSearch(){
    minimise('messageHistory')
}

//This function closes the delete message box
function abortDeleteMessage(){
    minimise('deleteMessage');
}

//This function closes the question reply box
function abortTeacherMessage(){
    minimise('questionReply');
}

//This function opens the inbox and closes the message histroy box
function teacherMessageInbox(){
    topFunction();
    maximise('classDisplay');
    minimise('messageHistory');
}

//This function opens the message history and closes the inbox and question reply box
function teacherMessageHistory(){
    topFunction();
    maximise('messageHistory');
    minimise('classDisplay');
    minimise('questionReply');
}

//This function closes all of the boxes on the admin page
function abort(){
    minimise('classAdd');
    minimise('addClassTitle');
    minimise('classDelete');
    minimise('classSearch');
    minimise('deleteClassTitle');
    minimise('updateClassTitle');
}

//This function forwards the selected class ID and title to the add class content
function addSend(addID,classTitle){
    topFunction();
    maximise('classAdd');
    maximise('addClassTitle');
    minimise('classDelete');
    minimise('classSearch');
    minimise('deleteClassTitle');
    minimise('updateClassTitle');
    var addTopicID = document.getElementById(addID).textContent;
    var classTitleElement = document.getElementById("className");
    var addClassName = document.getElementById(classTitle).textContent;
    classTitleElement.innerHTML = addClassName;
    document.getElementsByName("classAddID")[0].value = addTopicID;
}

//This function forwards the details of selected class content that user wants to update to the update content box
function updateSend(topicID, classTitle, postTitle, postDescription){
    topFunction();
    maximise('classSearch');
    maximise('updateClassTitle');
    minimise('classDelete');
    minimise('classAdd');
    minimise('addClassTitle');
    minimise('deleteClassTitle');
    var classTitleElement = document.getElementById("classNameSearch");


    var updateClassName = document.getElementById(classTitle).textContent;
    var updatePostTitle = document.getElementById(postTitle).textContent;
    var updatePostDescription = document.getElementById(postDescription).textContent;

    classTitleElement.innerHTML = updateClassName;

    var updateID = document.getElementById(topicID).textContent;
    document.getElementsByName("classUpdateID")[0].value = updateID;
    var updatePostTitle = document.getElementById(postTitle).textContent;
    document.getElementsByName("postNameSearch")[0].value = updatePostTitle;
    var updatePostDescription = document.getElementById(postDescription).textContent;
    document.getElementsByName("classDescriptionSearch")[0].value = updatePostDescription;
}

//This function fowards the details of the selected class content that the user wishes to delete to the deletion box
function deleteSend(topicID,classTitle,postName){
    topFunction();
    maximise('classDelete');
    maximise('deleteClassTitle');
    minimise('classAdd');
    minimise('classSearch');
    minimise('addClassTitle');
    minimise('updateClassTitle');
    var deleteIDElement = document.getElementsByName("postID");
    var classTitleElement = document.getElementById("classNameDelete");
    var postNameElement = document.getElementById("postNameDelete");

    var deleteID = document.getElementById(topicID).textContent;
    var deleteClassName = document.getElementById(classTitle).textContent;
    var deleteClassTitle = document.getElementById(postName).textContent;
    
    deleteIDElement.value = deleteID;
    classTitleElement.innerHTML = deleteClassName;
    postNameElement.innerHTML = deleteClassTitle;


    document.getElementsByName("classDeleteID")[0].value = deleteID;

}

//This function will forward the details of a question that the user wishes to remove to the deletion box
function messageDeleteSend(deleteMessageID, deleteMessageTo, deleteMessageSubject,deleteMessageQuestion){
    topFunction();
    maximise('deleteMessage');
    minimise('inbox');
    minimise('sent');
    var fromElement = document.getElementById("deleteMessageTo");
    var titleElement = document.getElementById("deleteMessageSubject");
    var descriptionElement = document.getElementById("deleteMessageQuestion");
    var sendDeleteID = document.getElementById(deleteMessageID).textContent;
    var sendDeleteTo = document.getElementById(deleteMessageTo).textContent;
    var sendDeleteSubject = document.getElementById(deleteMessageSubject).textContent;
    var sendDeleteQuestion = document.getElementById(deleteMessageQuestion).textContent;
    document.getElementsByName("messageDeleteID")[0].value = sendDeleteID;
    fromElement.innerHTML = sendDeleteTo;
    titleElement.innerHTML = sendDeleteSubject;
    descriptionElement.innerHTML = sendDeleteQuestion;
}

//This function sends the details of the class the user wants to delete to the delete box
function classDeleteSend(deleteClassID,deleteClassTitle,deleteClassDescription){
    topFunction();
    maximise('deleteClass');
    minimise('classViewer');
    var deleteName = document.getElementById("deleteClassName");
    var deleteDescription = document.getElementById("deleteClassDescription");
    var sendDeleteID = document.getElementById(deleteClassID).textContent;
    var sendDeleteTitle = document.getElementById(deleteClassTitle).textContent;
    var sendDeleteDescription = document.getElementById(deleteClassDescription).textContent;
    document.getElementsByName("classDeleteID")[0].value = sendDeleteID;
    document.getElementsByName("classDeleteTitle")[0].value = sendDeleteTitle;
    deleteName.innerHTML = sendDeleteTitle;
    deleteDescription.innerHTML = sendDeleteDescription;
}

//This function sends the details of the class the user wants to update to the update box
function updateClass(classIdElement, classTitleElement, classDescriptionElement){
    topFunction();
    maximise('updateClass');
    minimise('deleteClass');
    minimise('classAdd');
    minimise('classViewer');
    var classId = document.getElementById(classIdElement).textContent;
    var classTitle = document.getElementById(classTitleElement).textContent;
    var classDescription = document.getElementById(classDescriptionElement).textContent;
    document.getElementsByName("classUpdateID")[0].value = classId;
    document.getElementsByName("classUpdate")[0].value = classTitle;
    document.getElementsByName("classDescriptionUpdate")[0].value = classDescription;    
}

//This function sends the details of the quetion the user wants to reply to to the reply box
function replySend(QuestionID,QuestionSenderJav,QuestionTitleJav,QuestionDescriptionJav){
    topFunction();
    maximise('questionReply');
    var questionIDElement = document.getElementsByName("classID");
    var fromElement = document.getElementById("labelFrom");
    var titleElement = document.getElementById("labelSubject");
    var descriptionElement = document.getElementById("labelQuestion");
    var sendID = document.getElementById(QuestionID).textContent;
    var sendName = document.getElementById(QuestionSenderJav).textContent;
    var sendSubject = document.getElementById(QuestionTitleJav).textContent;
    var sendQuestion = document.getElementById(QuestionDescriptionJav).textContent;
    questionIDElement.value = sendID;

    document.getElementsByName("classID")[0].value = sendID;
    fromElement.innerHTML = sendName;
    titleElement.innerHTML = sendSubject;
    descriptionElement.innerHTML = sendQuestion;
}

//This function sends the details of the account the user wants to delete to the delete box
function deleteAccountSend(selectID,selectDeleteType,selectFname,selectLname,selectUsername,selectPassword){
    topFunction();
    maximise('accountDelete');
    minimise('accountAddClass')
    minimise('accountUpdate');

    var deleteOutputFName = document.getElementById("deleteOutputFName");
    var deleteOutputLName = document.getElementById("deleteOutputLName");
    var deleteOutputUsername = document.getElementById("deleteOutputUserName");
    var deleteOutputPassword = document.getElementById("deleteOutputPassword");
    var deleteOutputType = document.getElementById("deleteOutputType");

    var sendAccountID = document.getElementById(selectID).textContent;
    var sendType = document.getElementById(selectDeleteType).textContent;
    var sendFname = document.getElementById(selectFname).textContent;
    var sendLname = document.getElementById(selectLname).textContent;
    var sendUsername = document.getElementById(selectUsername).textContent;
    var sendPassword = document.getElementById(selectPassword).textContent;

    document.getElementsByName("accountDeleteID")[0].value = sendAccountID;
    document.getElementsByName("accountDeleteType")[0].value = sendType;
    deleteOutputFName.innerHTML = sendFname;
    deleteOutputLName.innerHTML = sendLname;
    deleteOutputUsername.innerHTML = sendUsername;
    deleteOutputPassword.innerHTML = sendPassword;
    deleteOutputType.innerHTML = sendType;
}

//This function closes the delete account box
function closeDeleteAccountSend(){
    minimise('accountDelete');
}

//This function sends the details of a selected account the user wants to update to the update box
function updateAccountSend(selectID,selectUpdateType,selectFname,selectLname,selectUsername,selectPassword){
    topFunction();
    minimise('accountDelete');
    minimise('accountAddClass')
    maximise('accountUpdate');

    var sendAccountID = document.getElementById(selectID).textContent;
    var sendType = document.getElementById(selectUpdateType).textContent;
    var sendFname = document.getElementById(selectFname).textContent;
    var sendLname = document.getElementById(selectLname).textContent;
    var sendUsername = document.getElementById(selectUsername).textContent;
    var sendPassword = document.getElementById(selectPassword).textContent;

    document.getElementsByName("accountUpdateID")[0].value = sendAccountID;
    document.getElementsByName("accountUpdateType")[0].value = sendType;
    document.getElementsByName("Fname")[0].value = sendFname;
    document.getElementsByName("Lname")[0].value = sendLname;
    document.getElementsByName("Username")[0].value = sendUsername;
    document.getElementsByName("Password")[0].value = sendPassword;
    document.getElementsByName("accountType")[0].value = sendType;
}

//This function sends the details of the account the user selected to add a class to the add class box
function addAccountSend(selectID, selectAddType){
    topFunction();
    maximise('accountAddClass');
    minimise('removeclass');
    minimise('accountDelete');
    minimise('accountUpdate');
    var sendAccountID = document.getElementById(selectID).textContent;
    var sendAccountType = document.getElementById(selectAddType).textContent;

    document.getElementsByName("accountAddClassID")[0].value = sendAccountID;
    document.getElementsByName("accountAddType")[0].value = sendAccountType;
}

//This function sends the details of the searched account and class details of the class the user wants to remove to the remove class box
function removeAccountClassSend(selectID,selectClassID,selectRemoveType,Title){
    topFunction();
    maximise('removeclass');
    minimise('accountAddClass');
    minimise('accountDelete');
    minimise('accountUpdate');

    var classTitleOutput = document.getElementById("classRemoveTitle");
    var sendAccountID = document.getElementById(selectID).textContent;
    var sendClassID = document.getElementById(selectClassID).value;
    var sendAccountType = document.getElementById(selectRemoveType).textContent;
    var sendAccountClassTitle = document.getElementById(Title).textContent;
    document.getElementsByName("accountRemoveClassID")[0].value = sendAccountID;
    document.getElementsByName("removeClassID")[0].value = sendClassID;
    document.getElementsByName("removeClassIDAccountType")[0].value = sendAccountType;
    classTitleOutput.innerHTML = sendAccountClassTitle;
}

//This functionsends the details of the searched account and class details of the class the user wants to update to the update class box
function updateClassAssign(selectID,selectClassID,selectUpdateClassType,Title){
    topFunction();
    maximise('updateAssignedClass');
    minimise('removeclass');
    minimise('accountAddClass');
    minimise('accountDelete');
    minimise('accountUpdate');

    var classTitleOutput = document.getElementById("displayOldClassTitle");

    var sendAccountID = document.getElementById(selectID).textContent;
    var sendClassID = document.getElementById(selectClassID).value;
    var sendAccountType = document.getElementById(selectUpdateClassType).textContent;
    var sendAccountClassTitle = document.getElementById(Title).textContent;

    document.getElementsByName("accountModifyClassID")[0].value = sendAccountID;
    document.getElementsByName("oldClassID")[0].value = sendClassID;
    document.getElementsByName("accountModifyType")[0].value = sendAccountType;
    classTitleOutput.innerHTML = sendAccountClassTitle;
}
