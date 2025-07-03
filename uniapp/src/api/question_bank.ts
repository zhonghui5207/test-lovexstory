import request from '@/utils/request'

// 我的题库
export const apiGetMyQuestionList = (params?: any) =>
    request.get({ url: '/questionbank/userQuestionbankLists', data: params })

// 题库分类列表
export const apiGetCategoryLists = (params?: any) =>
    request.get({ url: '/questionbank/categoryLists', data: params })

// 题库列表
export const apiQuestionBankList = (params?: any) =>
    request.get({ url: '/questionbank/lists', data: params }, { ignoreCancel: true })

//获取题目列表
export const apiGetTopicLists = (params?: any) =>
    request.get({ url: '/questionbank/topicLists', data: params })

//提交答案
export const apiHandleAnswer = (params?: any) =>
    request.post({ url: '/questionbank/answer', data: params })

//刷题报告
export const apiResport = (params?: any) =>
    request.get({ url: '/questionbank/report', data: params })

//交卷
export const submitAnswer = (params?: any) =>
    request.post({ url: '/questionbank/submit', data: params })

//重新做题
export const apiAnswerAgain = (params?: any) =>
    request.post({ url: '/questionbank/again', data: params })

//购买题目
export const apiBuyQuestionBank = (params?: any) =>
    request.post({ url: '/questionbank/buy', data: params })
