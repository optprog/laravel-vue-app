export const getToken = () => {
  const { token } = localStorage;
  if (token) {
    return token;
  }

  return false;
};

export const storeToken = token => {
  localStorage['token'] = token;
};
