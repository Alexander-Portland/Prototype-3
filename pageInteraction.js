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

//This function helps minimise a section that is identified by the contentID variable that is pa
function minimise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "none";
}

//This helps to 
function maximise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "block";
}

function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  }

function messageAdd(){
    maximise('classAdd');
    minimise('deleteMessage');
    minimise('inbox');
    minimise('sent');
}

function messageInbox(){
    maximise('inbox');
    minimise('classAdd');
    minimise('sent');
    minimise('deleteMessage');
}

function messageSent(){
    maximise('sent');
    minimise('classAdd');
    minimise('inbox');
    minimise('deleteMessage');
}

function viewClasses(){
    topFunction();
    maximise('classViewer');
    minimise('classAdd');
    minimise('addAcount');
}

function addClass(){
    topFunction();
    minimise('classViewer');
    minimise('deleteClass');
    maximise('classAdd');
    minimise('updateClass');
    minimise('addAcount');
}

function closeAddClass(){
    topFunction();
    minimise('classAdd');
}

function closeDeleteClass(){
    topFunction();
    minimise('deleteClass');
}

function closeUpdateClass(){
    topFunction();
    minimise('updateClass');
}

function addAccount(){
    maximise('addAcount');
    minimise('classViewer');
    minimise('deleteClass');
    minimise('classAdd');
    minimise('updateClass');
}

function abortMessage(){
    minimise('sent');
    minimise('classAdd');
    minimise('inbox');
    minimise('deleteMessage');

    var blank = "";
    document.getElementsByName("questionTitle")[0].value = blank;
    document.getElementsByName("sendQuestion")[0].value = blank;
}

function abortDeleteMessage(){
    minimise('deleteMessage');
}

function abortTeacherMessage(){
    minimise('questionReply');
}

function teacherMessageInbox(){
    topFunction();
    maximise('classDisplay');
    minimise('messageHistory');
}

function teacherMessageHistory(){
    topFunction();
    maximise('messageHistory');
    minimise('classDisplay');
    minimise('questionReply');
}

function abort(){
    minimise('classAdd');
    minimise('addClassTitle');
    minimise('classDelete');
    minimise('classSearch');
    minimise('deleteClassTitle');
    minimise('updateClassTitle');
}

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
    deleteName.innerHTML = sendDeleteTitle;
    deleteDescription.innerHTML = sendDeleteDescription;
}

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

    //var  = document.getElementById().textContent;
    //var  = document.getElementById("");
    //var  = document.getElementsByName("");
    
}

function closeDeleteAccountSend(){
    minimise('accountDelete');
}

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
    deleteOutputFName.innerHTML = sendFname;
}

function addAccountSend(selectID, selectAddType){
    topFunction();
    maximise('accountAddClass');

    minimise('accountDelete');
    minimise('accountUpdate');
    var sendAccountID = document.getElementById(selectID).textContent;
    var sendAccountType = document.getElementById(selectAddType).textContent;

    document.getElementsByName("accountAddClassID")[0].value = sendAccountID;
    document.getElementsByName("accountAddType")[0].value = sendAccountType;
}
