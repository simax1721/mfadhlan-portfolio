import { useCallback } from "react";
import { api } from "./lib/api";
import { useFetch } from "./hooks/useFetch";
import { useReveal } from "./hooks/useReveal";
import { useLocale } from "./i18n/useLocale";

import { Navbar } from "./components/Navbar";
import { Hero } from "./components/Hero";
import { About } from "./components/About";
import { Skills } from "./components/Skills";
import { Experience } from "./components/Experience";
import { Projects } from "./components/Projects";
import { EducationOrg } from "./components/EducationOrg";
import { Contact } from "./components/Contact";
import { Footer } from "./components/Footer";
import { BackToTop } from "./components/BackToTop";
import { FullPageLoader, ErrorScreen } from "./components/StatusScreens";

function App() {
  const { locale, t } = useLocale();
  const fetchBootstrap = useCallback(() => api.getBootstrap(locale), [locale]);
  const { data, loading, error } = useFetch(fetchBootstrap);

  const containerRef = useReveal<HTMLDivElement>([loading]);

  if (loading) return <FullPageLoader />;
  if (error || !data)
    return <ErrorScreen message={error ?? t("status.notFound")} />;

  return (
    <div ref={containerRef} className="min-h-screen bg-bg">
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-bg"
      >
        {t("nav.skipToContent")}
      </a>
      <Navbar profile={data.profile} />
      <main id="main-content">
        <Hero profile={data.profile} />
        <About profile={data.profile} />
        <Projects projects={data.projects} loading={false} />
        <Skills skills={data.skills} loading={false} />
        <Experience experiences={data.experiences} loading={false} />
        <EducationOrg
          education={data.education}
          organizations={data.organizations}
          loading={false}
        />
        <Contact profile={data.profile} />
      </main>
      <Footer profile={data.profile} />
      <BackToTop />
    </div>
  );
}

export default App;
