import { useEffect, useMemo, useState, type ReactNode } from "react";
import {
  LocaleContext,
  DICTIONARIES,
  STORAGE_KEY,
  getInitialLocale,
  lookup,
  type Locale,
  type LocaleContextValue,
} from "./locale-context";

export function LocaleProvider({ children }: { children: ReactNode }) {
  const [locale, setLocaleState] = useState<Locale>(getInitialLocale);

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, locale);
    document.documentElement.lang = locale;
  }, [locale]);

  const value = useMemo<LocaleContextValue>(
    () => ({
      locale,
      setLocale: setLocaleState,
      t: (key, vars) => {
        let text = lookup(DICTIONARIES[locale], key);
        if (vars) {
          for (const [name, val] of Object.entries(vars)) {
            text = text.replace(`{${name}}`, val);
          }
        }
        return text;
      },
    }),
    [locale],
  );

  return (
    <LocaleContext.Provider value={value}>{children}</LocaleContext.Provider>
  );
}
